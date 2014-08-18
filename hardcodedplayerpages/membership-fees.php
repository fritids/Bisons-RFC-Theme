<header>
<h2>Membership Fees</h2>
</header>

<?php
wp_enqueue_script('formscripts');

// Check whether a membership form has been filled out.
$current_form = new WP_Query ( array (
    'post_type' => 'membership_form',
    'posts_per_page' => 1,
    'orderby'   => 'date',
    'order'     => 'ASC',
    'author'    => get_current_user_id()
));


if ( ! $current_form->have_posts() ) : ?>
<p>Please take a moment to fill out our membership form (redirecting you now). Once this is done, you will be able to use this page to pay your fees.</p>
<script type='text/javascript'> setTimeout(function(){ document.location = 'players-area/membership-form/'; }, 3000); </script>
<?php else : ?>

<form>
    <fieldset>
        <?php if ( isset ( $_GET['paymethod'] ) ) :
       
       
          switch (  $_GET['paymethod']  ) {
           case "I've already paid":
               break;
           case "Monthly Direct Debit":
                $subscription_details = array(
                    'amount'           => $_GET['membershiptypemonthly'],
                    'name'             => "test",
                    'interval_length'  => 1,
                    'interval_unit'    => 'month',
                    'currency'         => 'GBP'
                );
               break;
           case "Single Payment":
                $subscription_details = array(
                    'amount'           => $_GET['membershiptypemonthly'],
                    'interval_length'  => 1,
                    'interval_unit'    => 'month',
                    'currency'         => 'GBP'
                );
               break;
       }

        
        $subscription_url = GoCardless::new_subscription_url($subscription_details); ?>
        <p>In a moment you will be redirected to an external website in order to setup a direct debit. Once you have completed this process, you will be returned here.</p>    
        <script type='text/javascript'> setTimeout(function(){ document.location = '<?php echo $subscription_url ?>'; }, 3000); </script>
        <?php else : ?>
        <p>You have not yet indicated how you will be paying your membership fees. Select a payment method below and click the 'next' button to set up a payment.</p>
        <div>
            <label class="smalllabel" for="paymethod">Payment Method</label>
            <select name="paymethod" id="paymethod">
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
            <select name="membershiptypemonthly" id="membershiptypemonthly">
            <?php foreach ($directdebitfees as $fee) : ?>
                <option value="<?php echo pence_to_pounds ( $fee['amount'], FALSE ) ?>"><?php echo $fee['name'] ?> - <?php echo pence_to_pounds ( $fee['amount'] ) ?> per month</option>
            <?php endforeach ?>
            </select>
        </div>
        <div id="mempaysingle" style="display:none">
            <label class="smalllabel" for="membershiptypesingle">Membership Type</label>
            <select name="membershiptypesingle" id="membershiptypesingle">
            <?php foreach ($singlepayment as $fee) : ?>
                <option value="<?php echo pence_to_pounds ( $fee['amount'], FALSE) ?>"><?php echo $fee['name'] ?> - <?php echo pence_to_pounds ( $fee['amount'] ) ?></option>
            <?php endforeach ?>
            </select>
        </div>
        <button type="submit">Next...</button>
        <?php endif; ?>

    </fieldset>
</form>
<?php endif ?>