<?php

    if( $_POST && ! isset( $_POST['edit_details']) ) 
    {
        
        $errors = array();
                   
        if( $_POST['firstname'] == '' ) $errors[] = "You forgot to enter a first name."; 
        if( $_POST['surname'] == '' ) $errors[] = "You forgot to enter a surname.";
        if( $_POST['dob-year'] == 0 || $_POST['dob-month'] == 0 || $_POST['dob-day'] == 0 ) $errors[] = "You didn't enter a valid date of birth.";
        if( $_POST['email_addy'] == '' ) $errors[] = "You forgot to enter an email address.";
        if( $_POST['contact_number'] == '' ) $errors[] = "You forgot to enter a contact number.";
        if( $_POST['streetaddy'] == '' ) $errors[] = "You forgot to enter your street address.";        
        if( $_POST['postcode'] == '' ) $errors[] = "You forgot to enter your postcode.";        
        if( $_POST['allergies'] == '' ) $errors[] = "You left the allergies box blank. If you have no allergies, please write 'none' in this box.";        
        if( $_POST['medication'] == '' ) $errors[] = "You left the medication box blank. If you are not on any medication, please write 'none' in this box.";
        if( $_POST['nokfirstname'] == '' ) $errors[] = "You left the 'first name' box for your next of kin blank.";
        if( $_POST['noksurname'] == '' ) $errors[] = "You left the 'surname' box for your next of kin blank.";
        if( $_POST['nokrelationship'] == '' ) $errors[] = "You left the 'relationship' box for your next of kin blank.";
        if( $_POST['nokcontactnumber'] == '' ) $errors[] = "You left the 'contact number' box for your next of kin blank.";
        if( $_POST['prevexperience'] == '' ) $errors[] = "You left the previous experience box blank. If you have no previous experience, please write 'none' in this box.";
        if( $_POST['otherfitness'] == '' ) $errors[] = "You left the other fitness box blank. If you do not do any other fitness activities, please write 'none' in this box.";
        if( $_POST['howdidyouhear'] == '' ) $errors[] = "Please tell us how you heard about the bisons.";
        if($_POST['codeofconduct'] != 'on' && ! isset($_POST['form_id']) ) $errors[] = "Please tick the box to show that you have read and agree to the code of conduct.";
        if($_POST['photographicpolicy'] != 'on'  && ! isset($_POST['form_id']) ) $errors[] = "Please tick the box to show that you have read and agree to the photographic policy.";
        
        if ( sizeof ( $errors) < 1 )
        {
            if ( isset($_POST['stripeToken']) )
            {
                $failed = false; 
                Stripe::setApiKey("sk_test_Hf90otvt8mMaJ18SFOAjjWVi");
                
                try {
                    $charge = Stripe_Charge::create(array(
                        "amount"        => $_POST['memtype'],
                        "currency"      => "gbp",
                        "card"          => $_POST['stripeToken'],
                        "description"   => "Bristol Bisons RFC Membership"
                    ));
                } catch(Stripe_CardError $e) {
                    $errors[] = $e;                        
                    $failed = true;
                }
            }
            
            if( ! $failed || ! isset($_POST['stripeToken']) )
            {
                $post = array(
                    'post_title'    => $_POST['firstname'].' '.$_POST['surname'].' '.date('Y'),
                    'post_type'     => 'membership_form',
                    'post_status'   => 'publish',
                );
                $post = $_POST['form_id'] ? $_POST['form_id'] : wp_insert_post( $post );
                
                update_post_meta($post, 'firstname', $_POST['firstname']);
                update_post_meta($post, 'surname', $_POST['surname']);
                update_post_meta($post, 'dob-day', $_POST['dob-day']);
                update_post_meta($post, 'dob-month', $_POST['dob-month']);
                update_post_meta($post, 'dob-year', $_POST['dob-year']);
                update_post_meta($post, 'email_addy', $_POST['email_addy']); 
                update_post_meta($post, 'contact_number', $_POST['contact_number']); 
                update_post_meta($post, 'streetaddy', $_POST['streetaddy']);  
                update_post_meta($post, 'postcode', $_POST['postcode']);
                update_post_meta($post, 'allergies', $_POST['allergies']);
                update_post_meta($post, 'medication', $_POST['medication']);
                update_post_meta($post, 'othermedicalstuff', $_POST['othermedicalstuff']);
                update_post_meta($post, 'nokfirstname', $_POST['nokfirstname']); 
                update_post_meta($post, 'noksurname', $_POST['noksurname']);
                update_post_meta($post, 'nokrelationship', $_POST['nokrelationship']);
                update_post_meta($post, 'nokcontactnumber', $_POST['nokcontactnumber']);
                update_post_meta($post, 'prevexperience', $_POST['prevexperience']);
                update_post_meta($post, 'otherfitness', $_POST['otherfitness']);
                update_post_meta($post, 'howdidyouhear', $_POST['howdidyouhear']);
                update_post_meta($post, 'current', 'true');
                update_post_meta($post, 'memtype', $_POST['memtype']);
            }
            
            if( ! $failed && isset($_POST['stripeToken']) )
                update_post_meta($post, 'paymentstatus', 3);
            else if ( $_POST['already'] == 'Yes' ) 
                update_post_meta($post, 'paymentstatus', 1);            
        }
    }
    
    $current_form = new WP_Query ( array (
        'post_type' => 'membership_form',
        'max_num_pages' => 1,
        'orderby'   => 'date',
        'author'    => get_current_user_id()
    ));
    
       
    
    while ( $current_form->have_posts() ) 
    {
        $current_form->the_post();
        $date = get_the_date();
        $form_id = get_the_id();
        $disabled = isset( $_POST['edit_details']) ? false : true; 
    }
?>

<header>
<h2>Bristol Bisons RFC Membership Form</h2>
</header>
<?php if ( $current_form->have_posts() ) : ?>
<p><strong>Please note that it is your responsibility to ensure that the information supplied below (particularly medical information) remains up to date</strong>. You can return to this form and make changes at any time; to do so, scroll down to the bottom and click 'Edit Details'. When you have finished, click 'Save Changes' and the committee will be notified of any changes you have made.</p>
<?php else: ?>
<p>Please take a moment to fill out the form below honestly and accurately. - note that all the information supplied on this form will remain completely <strong>confidential</strong>. Should you have any questions about anything on this form, please contact the <strong>membership secretary</strong>.</p>
<?php endif; ?>
<ul class='invalidformerrors'>
    <?php foreach ( $errors as $error ) : ?>
    <li><?php echo $error ?></li>
    <?php endforeach ?>
</ul>
<form id='membershipform_payment' method="post" role="form">
    
    <fieldset>
        <legend>Personal Details</legend>
        <div>
            <label class="smalllabel" for="firstname">First name</label>
            <input type="text" class="smalltextbox" name="firstname" id="firstname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'firstname', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="surname">Surname</label>
            <input type="text" class="smalltextbox" name="surname" id="surname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'surname', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="dob">Date of Birth</label>
             <select id="dob-year" name="dob-day">
                    <option value="0"></option>
                    <option value="1">1st</option>
                    <option value="2">2nd</option>
                    <option value="3">3rd</option>
                    <option value="4">4th</option>
                    <option value="5">5th</option>
                    <option value="6">6th</option>
                    <option value="7">7th</option>
                    <option value="8">8th</option>
                    <option value="9">9th</option>
                    <option value="10">10th</option>
                    <option value="11">11th</option>
                    <option value="12">12th</option>
                    <option value="13">13th</option>
                    <option value="14">14th</option>
                    <option value="15">15th</option>
                    <option value="16">16th</option>
                    <option value="17">17th</option>
                    <option value="18">18th</option>
                    <option value="19">19th</option>
                    <option value="20">20th</option>
                    <option value="21">21st</option>
                    <option value="22">22nd</option>
                    <option value="23">23rd</option>
                    <option value="24">24th</option>
                    <option value="25">25th</option>
                    <option value="26">26th</option>
                    <option value="27">27th</option>
                    <option value="28">28th</option>
                    <option value="29">29th</option>
                    <option value="30">30th</option>
                    <option value="31">31st</option>
                </select>
             <select id="dob-year" name="dob-month">
                    <option value="0"></option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            <select id="dob-year" name="dob-year">
                <option value="0"></option>
                <?php for ($i = 1901; $i < 2014; $i++ ) : ?>
                <option><?php echo $i ?></option>
                <?php endfor ?>
            </select>
        </div>
        <div>
            <label class="smalllabel" for="email_addy">Email</label>
            <input type="text" class="smalltextbox" name="email_addy" id="email_addy"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'email_addy', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="contact_number">Contact Number</label>
            <input type="text" class="smalltextbox" name="contact_number" id="contact_number"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'contact_number', true) ?>'<?php } ?>>
        </div>
    
        <div>
            <label for="streetaddy">Street address</label>
            <textarea name="streetaddy" id="streetaddy"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'streetaddy', true); } ?></textarea>
        </div>
        <div>
            <label  class="smalllabel" for="postcode">Postcode</label>
            <input type="text" class="smalltextbox" name="postcode" id="postcode"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'postcode', true) ?>'<?php } ?>>
        </div>
    </fieldset>
    <fieldset>
        <legend>Medical Information</legend>
        <p>If none, please write 'none' in the relevant box.</p>
        <div>
            <label for="allergies">Allergies</label>
            <textarea name="allergies" id="allergies"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'allergies', true); } ?></textarea>
        </div>
        <div>
            <label for="medication">Current Medication</label>
            <textarea name="medication" id="medication"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'medication', true); } ?></textarea>
        </div>
        <div>
            <label for="othermedicalstuff">Other Relevant Medical Information</label>
            <textarea name="othermedicalstuff" id="othermedicalstuff"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'othermedicalstuff', true); } ?></textarea>
        </div>
    </fieldset>
    <fieldset>
        <legend>Next of Kin</legend>
        <p>This person will be contacted in case of emergencies.</p>
        <div>
            <label class="smalllabel" for="nokfirstname">First name</label>
            <input type="text" class="smalltextbox" name="nokfirstname" id="nokfirstname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokfirstname', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="noksurname">Surname</label>
            <input type="text" class="smalltextbox" name="noksurname" id="noksurname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'noksurname', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="nokrelationship">Relationship</label>
            <input type="text" class="smalltextbox" name="nokrelationship" id="nokrelationship"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokrelationship', true) ?>'<?php } ?>>
        </div>
       <div>
            <label class="smalllabel" for="nok">Phone Number</label>
            <input type="text" class="smalltextbox" name="nokcontactnumber" id="nokcontactnumber"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokcontactnumber', true) ?>'<?php } ?>>
        </div>
    </fieldset>
    <fieldset>
        <legend>Experience and fitness</legend>
        <div>
            <label for="prevexperience">Previous rugby experience</label>
            <textarea name="prevexperience" id="prevexperience"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'prevexperience', true); } ?></textarea>
        </div>
        <div>
            <label for="otherfitness">Other sports / fitness involvement</label>
            <textarea name="otherfitness" id="otherfitness"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'otherfitness', true); } ?></textarea>
        </div>
    </fieldset>
    <fieldset>
        <legend>Other</legend>
        <div>
            <label for="howdidyouhear">How did you hear about The Bisons?</label>
            <textarea name="howdidyouhear" id="howdidyouhear"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'howdidyouhear', true); } ?></textarea>
        </div>
    </fieldset>
    <fieldset>
        <legend>Payment</legend>
        
        <?php if ( $current_form->have_posts() ) : ?>
        <div>
            <label>Payment Status</label>
            <?php 
            switch (get_post_meta($form_id, 'paymentstatus', true)) : 
                case 1: 
                ?><p class='formoutputlabel'>Manual payment (unverified)</p><?php
                break; 
                case 2: 
                ?><p class='formoutputlabel'>Manual payment (verified - payment received, thanks!)</p><?php
                break;             
                case 3: 
                ?><p class='formoutputlabel'>Online payment (Payment received, thanks!)</p><?php
                break;
             endswitch; ?>
        </div>        
        
        <div>
            <label>Membership Type</label>
            <?php 
            switch (get_post_meta($form_id, 'memtype', true)) : 
                case 70: 
                ?><p class='formoutputlabel'>£70 - Full Membership</p><?php
                break; 
                case 40: 
                ?><p class='formoutputlabel'>£70 - Concession Membership</p><?php
                break; 
             endswitch; ?>
        </div>      
        <?php else : ?>
        

        <span class="payment-errors"></span>
        <p>Note that payments are currently set to <strong>testing mode</strong> with Stripe (our payments processor). <a href='https://stripe.com/docs/testing'>This page</a> has a list of testing card numbers you can try it with.</p>
        <div>
            <label>Membership Type</label>
            <select id='memtype' name='memtype'>
                <option value="70">Full - £70</option>
                <option value="40">Concession - £40</option>
            </select>
        </div>
        
        <div>
            <label>Already Paid</label>
            <select name='already' id='already'>
                <option>No</option>
                <option>Yes</option>
            </select>
        </div>
        
        <div id="inputcarddetails">
            <div>
                <label class="smalllabel" for="cardnumber">Card Number</label>
                <input type="text" class="smalltextbox" id="cardnumber" maxlength="19" data-stripe="number" />
            </div>
            
            <div>
                <label class="smalllabel" for="cvc">CVC</label>
                <input type="text" class="cvc" id="cvc" maxlength="4" data-stripe="cvc" />
            </div>
            
            <div>
                <label class="smalllabel" for="expiry">Expiry date</label>
                <select data-stripe="exp-month">
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                
                <select data-stripe="exp-year">
                    <option>2014</option>
                    <option>2015</option>
                    <option>2016</option>
                    <option>2017</option>
                    <option>2018</option>
                    <option>2019</option>
                    <option>2020</option>
                    <option>2021</option>
                    <option>2022</option>
                    <option>2023</option>
                    <option>2024</option>
                    <option>2025</option>
                    <option>2026</option>
                    <option>2027</option>
                    <option>2028</option>
                    <option>2029</option>
                    <option>2030</option>
                    <option>2031</option>
                    <option>2032</option>
                    <option>2033</option>
                    <option>2034</option>
                    <option>2035</option>
                    <option>2036</option>
                    <option>2037</option>
                    <option>2038</option>
                    <option>2040</option>
                </select>
            </div>
        </div>
    <?php endif ?>
    </fieldset>
    <fieldset>
        <legend>Declaration and submission</legend>
        <div>
            <label class='checkboxlabel' for='codeofconduct'><input type="checkbox" name="codeofconduct" id="codeofconduct"<?php if ( $current_form->have_posts() ) { ?> disabled='true' checked='checked' <?php } ?>/>
I wish to become a member of the Bisons and have read and agree to abide by the club <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/players-area/code-of-conduct/'>code of conduct</a>.</label>
        </div>
        <div>
            <label class='checkboxlabel' for='photographicpolicy'><input type="checkbox" name="photographicpolicy" id="photographicpolicy"<?php if ( $current_form->have_posts() ) { ?> disabled='true' checked='checked' <?php } ?>/>
I have read and fully understand the club <a href='<?php echo $GLOBALS['blog_info']['url'] ?>/players-area/photographic-policy/'>photographic policy</a>.</label>
        </div>
        <div>
            <?php if ( $disabled ) : ?>
            <input type='submit' name='edit_details' <?php if ($current_form->have_posts() ) { echo "value='Edit Details' "; } ?>/></div>
            <?php else : ?>
            <button type='submit'><?php if ($current_form->have_posts() ) { echo "Save Changes"; } else { echo "Submit"; } ?></button></div>
            <?php endif ?>       
    </fieldset>
    <?php if ($current_form->have_posts() ) { ?><input type='hidden' name='form_id' value='<?php echo $form_id ?>' /><?php } ?>
    

</form>

