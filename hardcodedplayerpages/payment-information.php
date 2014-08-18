<?php 
    wp_enqueue_script('dynamicforms');
    wp_enqueue_script('formvalidation');

// Check whether a membership form has been filled out.
$current_form = new WP_Query ( array (
    'post_type' => 'membership_form',
    'posts_per_page' => 1,
    'orderby'   => 'date',
    'order'     => 'ASC',
    'author'    => get_current_user_id()
));

while ( $current_form->have_posts() ) 
{
    $current_form->the_post();
    $date = get_the_date();
    $form_id = get_the_id();
    $disabled = isset( $_POST['edit_details']) ? false : true; 
} 

if ($_POST['newsub'] == 'true')
{
  $return_addy = "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
  
  $user = array(
    'first_name'            => get_post_meta( $form_id, 'firstname', true ),
    'last_name'             => get_post_meta( $form_id, 'surname', true ),
    'email'                 => get_post_meta( $form_id, 'email_addy', true ),
    'billing_address1'      => get_post_meta( $form_id, 'streetaddyl1', true ),
    'billing_address2'      => get_post_meta( $form_id, 'streetaddyl2', true ),
    'billing_town'          => get_post_meta( $form_id, 'streetaddytown', true ),
    'billing_postcode'      => get_post_meta( $form_id, 'postcode', true ),
  );

  switch (  $_POST['paymethod']  ) 
  {
       case "I've already paid":
           break;
       
       case "Monthly Direct Debit":
            $subscription_details = array(
                'amount'           => pence_to_pounds ( get_post_meta( $_POST['membershiptypemonthly'], 'fee-amount', true ), false ),
                'name'             => get_post_meta( $_POST['membershiptypemonthly'], 'fee-name', true ),
                'interval_length'  => 1,
                'interval_unit'    => 'month',
                'currency'         => 'GBP',
                'user'             => $user,
                'state'            => $form_id . "+DD",
                'redirect_uri'     => $return_addy
            );
           break;
       
       case "Single Payment":
            $subscription_details = array(
                'amount'           => pence_to_pounds ( get_post_meta( $_POST['membershiptypesingle'], 'fee-amount', true ), false ),
                'name'             => get_post_meta( $_POST['membershiptypemonthly'], 'fee-name', true ),
                'currency'         => 'GBP',
                'user'             => $user, 
                'state'            => $form_id . "+DD",
                'redirect_uri'     => $return_addy
            );
           break;
    }

    $gocardless_url = GoCardless::new_subscription_url($subscription_details);
}

// If there is a resource_id in the querystring, it must returning from Gocardless, so confirm the payment and then save the resource information if it confirms properly
if ( isset ( $_GET['resource_id'] ) )
{
    $confirm_params = array(
      'resource_id'    => $_GET['resource_id'],
      'resource_type'  => $_GET['resource_type'],
      'resource_uri'   => $_GET['resource_uri'],
      'signature'      => $_GET['signature']
    );
    
    if (isset($_GET['state'])) {
      $confirm_params['state'] = $_GET['state'];
    }
    
    $confirmed_resource = GoCardless::confirm_resource($confirm_params);
    
    if ( $confirmed_resource )
    {
        $state = explode ('+', $_GET['state']);
        $the_post = $state[0];
        $type = $state[1];
        $post_author = get_post_field ( 'post_author', $the_post );
        
        switch ( $type )
        {
            
            case "DD": 
                
                update_post_meta($the_post, 'payment_type', "Direct Debit" ); 
                $resource = GoCardless_Subscription::find($_GET['resource_id']);
                update_post_meta($the_post, 'payment_status', 7 );  // DD created, not yet taken payments
                
            break;
            
            case "SP": 
                update_post_meta($the_post, 'payment_type', "Single Payment" );
                $resource = GoCardless_Bill::find($_GET['resource_id']);
                update_post_meta($the_post, 'payment_status', 2 );  // Single payment pending         
            break;
            
        }
        
        // If user is a guest player, upgrade them
        if ( check_user_role( 'guest_player' ) )
        {
            $user = new WP_User($post_author);
            $user->remove_role( 'guest_player');
            $user->add_role( 'player');
        }
        
        update_post_meta($the_post, 'gcl_sub_id', $_GET['resource_id'] );
        update_post_meta($the_post, 'gcl_sub_uri', $_GET['resource_uri'] );
        update_post_meta($the_post, 'mem_name', $resource->name );
        update_post_meta($the_post, 'mem_status', 'Active' );
    }
}
?>
<header>
<h2>Payment Information</h2>
</header>
<?php if ($gocardless_url) : ?>
<p class="flashmessage">In a moment, you will be redirected to a direct debit mandate form at GoCardless. Once you have finished setting up your payment information, you will be returned to this site. See you in a bit!</p>
<script type='text/javascript'> setTimeout(function(){ document.location = '<?php echo $gocardless_url ?>'; }, 3000); </script>
<?php endif ?>

<?php if ($confirmed_resource) : ?>
<p class="flashmessage">Congratulations! Your direct debit (or full payment) has now been setup - you should receive an email from GoCardless (our payment processor) very shortly. 
<?php endif ?>   
<?php
if ( $_POST['confirm'] == 'true')
{
    $sub_id = get_post_meta ( $form_id, 'gcl_sub_id', true);
    GoCardless_Subscription::find( $sub_id )->cancel();
    update_post_meta($form_id, 'payment_status', 9);
    update_post_meta($form_id, 'mem_status', 'Inactive' );
    
    echo "<p class='flashmessage'>Your subscription has been cancelled. You can return to this page at a later stage to reinstate it. Please note that you may also need to cancel the Direct Debit with your bank</p>";
}

if ( ! $current_form->have_posts() ) : ?>
    <p>Please take a moment to fill out our membership form (redirecting you now). Once this is done, you will be able to use this page to pay your fees.</p>
    <script type='text/javascript'> setTimeout(function(){ document.location = 'players-area/membership-form/'; }, 3000); </script>
    <?php else : ?>
    <p>Should you wish to modify or cancel your payment details, please contact a member of the committee.</p>
    <table>
        <tbody>
            <tr>
                <th>Membership Type</th>
                <td><?php echo get_post_meta ( $form_id, 'mem_name', true) ?></td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td><?php echo get_post_meta ( $form_id, 'payment_type', true) ?></td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td><?php global $payment_statuses; echo $payment_statuses[ get_post_meta($form_id, 'payment_status', true) ] ?></td>
            </tr>
            <tr>
                <th>Membership Status</th>
                <td><?php echo get_post_meta ( $form_id, 'mem_status', true) ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php if ( get_post_meta ( $form_id, 'mem_status', true) == 'Inactive' ) : ?>
    <form method="POST">
    <fieldset>
        <legend>Re-establish payment</legend>
        <p class="info">Your membership is inactive. Either you have cancelled your membership, or payments have failed when we requested them. To re-establish a subscription, select a membership type and press the button below to be redirected to our payment processor.</p>
        <div>
            <label class="smalllabel" for="paymethod">Payment Method</label>
            <select class="mustselect" name="paymethod" id="paymethod">
                <option></option>
                <option>I've already paid</option>
                <option>Monthly Direct Debit</option>
                <option>Single Payment</option>
            </select>
        </div>
        
        <?php 
        $fees = new WP_Query ( array( 'post_type' => 'membership_fee' ));
        while ( $fees->have_posts() ) 
        {
            $fees->the_post();
            
            $the_fee = array (
                'id'    => get_the_id(),
                'name' => get_post_meta( get_the_id(), 'fee-name', true),
                'amount' => get_post_meta( get_the_id(), 'fee-amount', true),
                'description' => get_post_meta( get_the_id(), 'fee-description', true)
            );
                
            if ( get_post_meta( get_the_id(), 'fee-type', true) == "Monthly Direct Debit") $directdebitfees[] = $the_fee;
            else $singlepayment[] = $the_fee;
            
        }
?>
        

        <div id="mempaymonthly" style="display:none">
            <label class="smalllabel" for="membershiptypemonthly">Membership Type</label>
            <select class="mustselect" name="membershiptypemonthly" id="membershiptypemonthly">
                <option></option>
            <?php foreach ($directdebitfees as $fee) : ?>
                <option value="<?php echo $fee['id'] ?> ?>"><?php echo $fee['name'] ?> - <?php echo pence_to_pounds ( $fee['amount'] ) ?> per month</option>
            <?php endforeach ?>
            </select>
        </div>
        <div id="mempaysingle" style="display:none">
            <label class="smalllabel" for="membershiptypesingle">Membership Type</label>
            <select class="mustselect" name="membershiptypesingle" id="membershiptypesingle">
                <option></option>
            <?php foreach ($singlepayment as $fee) : ?>
                <option value="<?php echo $fee['id'] ?>"><?php echo $fee['name'] ?> - <?php echo pence_to_pounds ( $fee['amount'] ) ?></option>
            <?php endforeach ?>
            </select>
        </div>
        <button type="submit" name="newsub" value='true'>Next</button>
    </fieldset>
    </form>
    <?php elseif ( get_post_meta ( $form_id, 'payment_type', true) == "Direct Debit") : ?>
        <form method="POST">
            <?php if ( $_POST['cancel'] == 'true') : ?>
            <button type='submit'>Don't Cancel</button>
            <button type='submit' name='confirm' value='true'>Confirm Cancellation</button>
            <?php else : ?>
            <button type='submit' name='cancel' value='true'>Cancel Subscription</button>
            <?php endif ?>
        </form>
    <?php endif ?>
<?php endif ?>