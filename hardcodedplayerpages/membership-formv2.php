<?php
    wp_enqueue_script('stripe');
    wp_enqueue_script('formscripts');

    if( $_POST && ! isset( $_POST['edit_details']) ) 
    {
        $errors = array();
                   

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
                update_post_meta($post, 'gender', $_POST['gender']);
                if ( $_POST['gender'] == "Other" ) update_post_meta($post, 'gender', $_POST['othergender']);
                update_post_meta($post, 'dob-day', $_POST['dob-day']);
                update_post_meta($post, 'dob-month', $_POST['dob-month']);
                update_post_meta($post, 'dob-year', $_POST['dob-year']);
                update_post_meta($post, 'email_addy', $_POST['email_addy']); 
                update_post_meta($post, 'contact_number', $_POST['contact_number']); 
                update_post_meta($post, 'streetaddy', $_POST['streetaddy']);  
                update_post_meta($post, 'postcode', $_POST['postcode']);
                
                update_post_meta($post, 'medconsdisabyesno', $_POST['medconsdisabyesno']);
                if ($_POST['medconsdisabyesno'] == "Yes")
                { 
                    for ( $i = 1; isset( $_POST['condsdisablities_name_row' . $i] ); $i++ )
                    {
                        update_post_meta($post, 'condsdisablities_name_row' . $i, $_POST['condsdisablities_name_row' . $i]);
                        update_post_meta($post, 'condsdisablities_drugname_row' . $i, $_POST['condsdisablities_drugname_row' . $i]);
                        update_post_meta($post, 'condsdisablities_drugdose_freq_row' . $i, $_POST['condsdisablities_drugdose_freq_row' . $i]);
                        update_post_meta($post, 'condsdisablities_rowcount', $i);
                    }
                }
                update_post_meta($post, 'allergiesyesno', $_POST['allergiesyesno']);
                
                if ($_POST['allergiesyesno'] == "Yes")
                { 
                    for ( $i = 1; isset( $_POST['allergies_name_row' . $i] ); $i++ )
                    {
                        update_post_meta($post, 'allergies_name_row' . $i, $_POST['allergies_name_row' . $i]);
                        update_post_meta($post, 'allergies_drugname_row' . $i, $_POST['allergies_drugname_row' . $i]);
                        update_post_meta($post, 'allergies_drugdose_freq_row' . $i, $_POST['allergies_drugdose_freq_row' . $i]);
                        update_post_meta($post, 'allergies_rowcount', $i);
                    }
                }

                update_post_meta($post, 'injuredyesno', $_POST['injuredyesno']);
                

                if ($_POST['injuredyesno'] == "Yes")
                { 
                    for ( $i = 1; isset( $_POST['injuries_name_row' . $i] ); $i++ )
                    {
                        update_post_meta($post, 'injuries_name_row' . $i, $_POST['injuries_name_row' . $i]);
                        update_post_meta($post, 'injuries_when_row' . $i, $_POST['injuries_when_row' . $i]);
                        update_post_meta($post, 'injuries_treatmentreceived_row' . $i, $_POST['injuries_treatmentreceived_row' . $i]);
                        update_post_meta($post, 'injuries_who_row' . $i, $_POST['injuries_who_row' . $i]);
                        update_post_meta($post, 'injuries_status_row' . $i, $_POST['injuries_status_row' . $i]);
                        update_post_meta($post, 'injuries_rowcount', $i);
                    }
                }
                update_post_meta($post, 'nokfirstname', $_POST['nokfirstname']); 
                update_post_meta($post, 'noksurname', $_POST['noksurname']);

                update_post_meta($post, 'nokrelationship', $_POST['nokrelationship']);
                update_post_meta($post, 'nokcontactnumber', $_POST['nokcontactnumber']);
                
                update_post_meta($post, 'sameaddress', $_POST['sameaddress']);
                
                if ( $_POST['sameaddress'] == "Yes" ) 
                {
                    update_post_meta($post, 'nokstreetaddy', $_POST['nokstreetaddy']);
                    update_post_meta($post, 'nokpostcode', $_POST['nokpostcode']);

                }          
                update_post_meta($post, 'othersports', $_POST['othersports']);
                update_post_meta($post, 'hoursaweektrain', $_POST['hoursaweektrain']);
                update_post_meta($post, 'playedbefore', $_POST['playedbefore']);
                if( $_POST ['playedbefore'] == "Yes") update_post_meta($post, 'whereandseasons', $_POST['whereandseasons']);
                update_post_meta($post, 'height', $_POST['height']);
                update_post_meta($post, 'weight', $_POST['weight']);
                
                
                update_post_meta($post, 'fainting', $_POST['fainting']);
                update_post_meta($post, 'dizzyturns', $_POST['dizzyturns']);
                update_post_meta($post, 'breathlessness', $_POST['breathlessness']);
                update_post_meta($post, 'bloodpressure', $_POST['bloodpressure']);
                update_post_meta($post, 'diabetes', $_POST['diabetes']);
                update_post_meta($post, 'palpitations', $_POST['palpitations']);
                update_post_meta($post, 'chestpain', $_POST['chestpain']);
                update_post_meta($post, 'suddendeath', $_POST['suddendeath']);
                
                update_post_meta($post, 'smoking', $_POST['smoking']);
                if( $_POST ['smoking'] == "Yes") update_post_meta($post, 'howmanycigsperday', $_POST['howmanycigsperday']);
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
?>

<header>
<h2>Bristol Bisons RFC Membership Form</h2>
</header>
<?php if ( $current_form->have_posts() ) : ?>
<p><strong>Please note that it is your responsibility to ensure that the information supplied below (particularly medical information) remains up to date</strong>. You can return to this form and make changes at any time; to do so, scroll down to the bottom and click 'Edit Details'. When you have finished, click 'Save Changes' and the committee will be notified of any changes you have made.</p>
<?php else: ?>
<p>Please take a moment to fill out the form below. Note that all the information supplied will remain completely <strong>confidential</strong>. Should you have any questions about anything on this form, please contact the <strong>membership secretary</strong>.</p>
<?php endif; ?>
<ul class='invalidformerrors'>
    <?php foreach ( $errors as $error ) : ?>
    <li><?php echo $error ?></li>
    <?php endforeach ?>
</ul>
<form id='membershipform_payment' method="post" role="form">
    
    <?php if ($disabled) : ?>
    <input type="hidden" name="disabled" id="disabled" value="true" />
    <?php endif ?>
    
    <fieldset>
        <legend>Personal Details</legend>
        <div>
            <label class="smalllabel" for="firstname">First name</label>
            <input type="text" class="smalltextbox notempty" name="firstname" id="firstname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'firstname', true) ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="surname">Surname</label>
            <input type="text" class="smalltextbox notempty" name="surname" id="surname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'surname', true) ?>'<?php } ?> />
        </div>
        <div>
            <label>Gender</label>
            <select class="mustselect" name='gender' id='gender' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Male") { echo " selected='selected'"; } ?>>Male</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Female") { echo " selected='selected'"; } ?>>Female</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Other") { echo " selected='selected'"; } ?>>Other</option>
            </select>
        </div>
        <div id="othergender"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'gender', true) == "Other") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="othergender">Other Gender Details</label>
            <input type="text" class="smalltextbox notempty" name="othergender" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'othergender', true) ?>'<?php } ?> />
            <p class="forminfo">As a fully inclusive rugby club, we completely recognise that a gender designation of 'male' or 'female' is far too simplistic for the real world. However, because we are a rugby team, we are bound by <a href='http://www.rfu.com/' title='RFU Website'>RFU</a> regulations which unfortunately are categorised in simple male/female terms. Please be aware therefore that only a person who self-identifies as 'male' in some way can play in 'male' rugby. Likewise, only a person who self-identifies as 'female' in some way can play in 'female' rugby.</p>
        </div>
        <div>
            <label class="smalllabel" for="dob">Date of Birth</label>
             <div class="inlinediv">
             <select class="norightmargin" id="dob-year" name="dob-day" <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                    <option value="0"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "0") { echo " selected='selected'"; } ?>></option>
                    <option value="1"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "1") { echo " selected='selected'"; } ?>>1st</option>
                    <option value="2"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "2") { echo " selected='selected'"; } ?>>2nd</option>
                    <option value="3"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "3") { echo " selected='selected'"; } ?>>3rd</option>
                    <option value="4"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "4") { echo " selected='selected'"; } ?>>4th</option>
                    <option value="5"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "5") { echo " selected='selected'"; } ?>>5th</option>
                    <option value="6"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "6") { echo " selected='selected'"; } ?>>6th</option>
                    <option value="7"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "7") { echo " selected='selected'"; } ?>>7th</option>
                    <option value="8"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "8") { echo " selected='selected'"; } ?>>8th</option>
                    <option value="9"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "9") { echo " selected='selected'"; } ?>>9th</option>
                    <option value="10"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "10") { echo " selected='selected'"; } ?>>10th</option>
                    <option value="11"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "11") { echo " selected='selected'"; } ?>>11th</option>
                    <option value="12"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "12") { echo " selected='selected'"; } ?>>12th</option>
                    <option value="13"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "13") { echo " selected='selected'"; } ?>>13th</option>
                    <option value="14"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "14") { echo " selected='selected'"; } ?>>14th</option>
                    <option value="15"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "15") { echo " selected='selected'"; } ?>>15th</option>
                    <option value="16"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "16") { echo " selected='selected'"; } ?>>16th</option>
                    <option value="17"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "17") { echo " selected='selected'"; } ?>>17th</option>
                    <option value="18"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "18") { echo " selected='selected'"; } ?>>18th</option>
                    <option value="19"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "19") { echo " selected='selected'"; } ?>>19th</option>
                    <option value="20"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "20") { echo " selected='selected'"; } ?>>20th</option>
                    <option value="21"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "21") { echo " selected='selected'"; } ?>>21st</option>
                    <option value="22"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "22") { echo " selected='selected'"; } ?>>22nd</option>
                    <option value="23"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "23") { echo " selected='selected'"; } ?>>23rd</option>
                    <option value="24"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "24") { echo " selected='selected'"; } ?>>24th</option>
                    <option value="25"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "25") { echo " selected='selected'"; } ?>>25th</option>
                    <option value="26"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "26") { echo " selected='selected'"; } ?>>26th</option>
                    <option value="27"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "27") { echo " selected='selected'"; } ?>>27th</option>
                    <option value="28"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "28") { echo " selected='selected'"; } ?>>28th</option>
                    <option value="29"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "29") { echo " selected='selected'"; } ?>>29th</option>
                    <option value="30"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "30") { echo " selected='selected'"; } ?>>30th</option>
                    <option value="31"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-day', true) == "31") { echo " selected='selected'"; } ?>>31st</option>
                </select>
             <select class="norightmargin" id="dob-year" name="dob-month" <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                    <option value="0"></option>
                    <option value="01"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "01") { echo " selected='selected'"; } ?>>January</option>
                    <option value="02"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "02") { echo " selected='selected'"; } ?>>February</option>
                    <option value="03"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "03") { echo " selected='selected'"; } ?>>March</option>
                    <option value="04"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "04") { echo " selected='selected'"; } ?>>April</option>
                    <option value="05"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "05") { echo " selected='selected'"; } ?>>May</option>
                    <option value="06"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "06") { echo " selected='selected'"; } ?>>June</option>
                    <option value="07"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "07") { echo " selected='selected'"; } ?>>July</option>
                    <option value="08"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "08") { echo " selected='selected'"; } ?>>August</option>
                    <option value="09"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "09") { echo " selected='selected'"; } ?>>September</option>
                    <option value="10"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "10") { echo " selected='selected'"; } ?>>October</option>
                    <option value="11"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "11") { echo " selected='selected'"; } ?>>November</option>
                    <option value="12"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-month', true) == "12") { echo " selected='selected'"; } ?>>December</option>
                </select>
            <select class="norightmargin id="dob-year" name="dob-year" <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option value="0"></option>
                <?php for ($i = 1901; $i < 2014; $i++ ) : ?>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'dob-year', true) == $i) { echo " selected='selected'"; } ?>><?php echo $i ?></option>
                <?php endfor ?>
            </select>
            </div>
        </div>
        <div>
            <label class="smalllabel" for="email_addy">Email</label>
            <input type="text" class="smalltextbox notempty" name="email_addy" id="email_addy"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'email_addy', true) ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="contact_number">Contact Number</label>
            <input type="text" class="smalltextbox notempty" name="contact_number" id="contact_number"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'contact_number', true) ?>'<?php } ?> />
        </div>
    
        <div>
            <label for="streetaddy">Street address</label>
            <textarea name="streetaddy" class="notempty" id="streetaddy"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'streetaddy', true); } ?></textarea>
        </div>
        <div>
            <label  class="smalllabel" for="postcode">Postcode</label>
            <input type="text" class="smalltextbox notempty" name="postcode" id="postcode"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'postcode', true) ?>'<?php } ?> />
        </div>
    </fieldset>
    <fieldset>
        <legend>Medical Declaration</legend>
        <p>Please answer the next few sections as accurately and honestly as you can. In the very rare event of some kind of injury occurring, this information will help insure that medical professionals are able to do their job properly. </p>
        <div>
            <label>Do you have any current medical conditions or disabilities?</label>
            <select class="mustselect" name='medconsdisabyesno' id='medconsdisabyesno' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'medconsdisabyesno', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'medconsdisabyesno', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
            <p class="forminfo">For example, asthma, diabetes, epilepsy, anaemia, haemophilia, viral illness, etc.</p>
        </div>

        <div>
            <label>Do you have any allergies?</label>
            <select class="mustselect" name='allergiesyesno' id='allergiesyesno' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'allergiesyesno', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'allergiesyesno', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
            <p class="forminfo">For example, bee-stings, peanut butter etc.</p>
        </div>
        <div>
            <label>Have you ever been injured?</label>
            <select class="mustselect" name='injuredyesno' id='injuredyesno' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'injuredyesno', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'injuredyesno', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
            <p class="forminfo">For example, concussion or a broken rib.</p>
        </div>
    </fieldset>
    
    <fieldset id="conddisablefieldset"<?php if (get_post_meta($form_id, 'medconsdisabyesno', true) == "Yes") { ?> style="display:block;"<?php } ?>>
        <legend>Conditions or disabilities</legend>
        <p>Please enter the details of your condition or disability, and any medication (e.g. tablets, inhalers or creams) you take for each condition, making sure to give drug names.</p>
        <table id="conditionsdisabilitiestable">
            <thead>
                <tr>
                    <th>Condition or disability</th>
                    <th>Medication</th>
                    <th>Dose and frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_post_meta($form_id, 'condsdisablities_rowcount', true); $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input name="condsdisablities_name_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'condsdisablities_name_row' . $i, true); } ?>" /></td>
                    <td><input name="condsdisablities_drugname_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'condsdisablities_drugname_row' . $i, true); } ?>" /></td>
                    <td><input name="condsdisablities_drugdose_freq_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'condsdisablities_drugdose_freq_row' . $i, true); } ?>" /></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <?php if ( ! $disabled ) { ?>
        <button class="smallbutton removerow"<?php if ( get_post_meta($form_id, 'condsdisablities_rowcount', true) > 1 ) { ?> style='display:inline'<?php } ?>>Remove Row</button>
        <button class="smallbutton addrow">Add Row</button>
        <?php } ?>
    </fieldset>
    <fieldset id="allergiesfieldset"<?php if (get_post_meta($form_id, 'allergiesyesno', true) == "Yes") { ?> style="display:block;"<?php } ?>>
        <legend>Allergies</legend>
        <p>Please enter the details of your allergy, and any medication (e.g. tablets, inhalers, creams) you use for each, making sure to give drug names.</p>
        <table id="allergiestable">
            <thead>
                <tr>
                    <th>Allergy</th>
                    <th>Medication</th>
                    <th>Dose and frequency</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_post_meta($form_id, 'allergies_rowcount', true); $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input name="allergies_name_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'allergies_name_row' . $i, true); } ?>" /></td>
                    <td><input name="allergies_drugname_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'allergies_drugname_row' . $i, true); } ?>" /></td>
                    <td><input name="allergies_drugdose_freq_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'allergies_drugdose_freq_row' . $i, true); } ?>" /></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <?php if ( ! $disabled ) { ?>
        <button class="smallbutton removerow"<?php if ( get_post_meta($form_id, 'allergies_rowcount', true) > 1 ) { ?> style='display:inline'<?php } ?>>Remove Row</button>
        <button class="smallbutton addrow">Add Row</button>
        <?php } ?>
    </fieldset>
        <fieldset id="injuriesfieldset"<?php if (get_post_meta($form_id, 'injuredyesno', true) == "Yes") { ?> style="display:block;"<?php } ?>>
        <legend>Injuries</legend>
        <p>Please list any injuries (e.g. concussion), indicating when they happened, who treated you (e.g. your doctor) and the current status of your injuries (e.g. whether they are fully recovered or not).</p>
        <table id="injuriestable">
            <thead>
                <tr>
                    <th>Injury</th>
                    <th>When</th>
                    <th>Treatment received</th>
                    <th>Who treated you</th>
                    <th>Current status of injury</th>
                </tr>
            </thead>
            <tbody>
                <?php for ( $i = 1; $i == 1 || $i <= get_post_meta($form_id, 'injuries_rowcount', true); $i++ ) : ?>
                <tr class='clonerow'>
                    <td><input name="injuries_name_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_name_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_when_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_when_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_treatmentreceived_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_treatmentreceived_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_who_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_who_row' . $i, true); } ?>" /></td>
                    <td><input name="injuries_status_row<?php echo $i; ?>" type='text' <?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value="<?php echo get_post_meta($form_id, 'injuries_status_row' . $i, true); } ?>" /></td>

                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
        <?php if ( ! $disabled ) { ?>            
        <button class="smallbutton removerow"<?php if ( get_post_meta($form_id, 'injuries_rowcount', true) > 1 ) { ?> style='display:inline'<?php } ?>>Remove Row</button>
        <button class="smallbutton addrow">Add Row</button>
        <?php } ?>
    </fieldset>

    <fieldset>
        <legend>Next of Kin</legend>
        <p>This person will be contacted in case of emergencies.</p>
        <div>
            <label class="smalllabel" for="nokfirstname">First name</label>
            <input type="text" class="smalltextbox notempty" name="nokfirstname" id="nokfirstname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokfirstname', true) ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="noksurname">Surname</label>
            <input type="text" class="smalltextbox notempty" name="noksurname" id="noksurname"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'noksurname', true) ?>'<?php } ?> />
        </div>
        <div>
            <label class="smalllabel" for="nokrelationship">Relationship</label>
            <input type="text" class="smalltextbox notempty" name="nokrelationship" id="nokrelationship"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokrelationship', true) ?>'<?php } ?> />
        </div>
       <div>
            <label class="smalllabel" for="nok">Phone Number</label>
            <input type="text" class="smalltextbox notempty" name="nokcontactnumber" id="nokcontactnumber"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokcontactnumber', true) ?>'<?php } ?> />
        </div>
        <div>
            <label>Lives at same address</label>
            <select name='sameaddress' id='sameaddress' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'sameaddress', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'sameaddress', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>
        </div>
        <div id="nokaddygroup"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'sameaddress', true) == "No") { ?> style="display:block"<?php } ?>>
            <div>
                <label for="nokstreetaddy">Street address</label>
                <textarea name="nokstreetaddy notempty" id="nokstreetaddy"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'nokstreetaddy', true); } ?></textarea>
            </div>
            <div>
                <label  class="smalllabel" for="nokpostcode">Postcode</label>
                <input type="text" class="smalltextbox notempty" name="nokpostcode" id="nokpostcode"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'nokpostcode', true) ?>'<?php } ?> />
            </div>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Health and Fitness Assessment</legend>
        <div>
            <label class="smalllabel" for="othersports">In which other sports or physical activities are you involved?</label>
            <input type="text" class="smalltextbox notempty" name="othersports" id="othersports"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'othersports', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="hoursaweektrain">How many hours a week do you train?</label>
            <input type="text" class="smalltextbox notempty" name="hoursaweektrain" id="hoursaweektrain"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'hoursaweektrain', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="playedbefore">Have you played rugby before?</label>
            <select name='playedbefore' id='playedbefore' <?php if ( $disabled ) { ?> disabled='true'<?php } ?>>
                <option></option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'playedbefore', true) == "No") { echo " selected='selected'"; } ?>>No</option>
                <option<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'playedbefore', true) == "Yes") { echo " selected='selected'"; } ?>>Yes</option>
            </select>        
        </div>
        <div id="howmanyseasonsgroup"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'playedbefore', true) == "Yes") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="whereandseasons">Where did you play and for how many seasons?</label>
            <input type="text" class="smalltextbox notempty" name="whereandseasons" id="whereandseasons"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'whereandseasons', true) ?>'<?php } ?>>
        </div>
        <div>
            <label class="smalllabel" for="height">Height</label>
            <input type="text" class="smalltextbox notempty" name="height" id="height"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'height', true) ?>'<?php } ?>>
            <p class="forminfo">Please make sure to indicate units</p>
        </div>
        <div>
            <label class="smalllabel" for="weight">Weight</label>
            <input type="text" class="smalltextbox notempty" name="weight" id="weight"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'weight', true) ?>'<?php } ?>>
            <p class="forminfo">Please make sure to indicate units</p>

        </div>
    </fieldset>
    
    <fieldset>
        <legend>Cardiac Questionairre</legend>
        <p>Please tick each box that applies to you.</p>
        <div>
        <label><input type="checkbox" name="fainting" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'fainting', true) == "on") { ?> checked="checked"<?php } ?> />Fainting</label>
        <label><input type="checkbox" name="dizzyturns" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'dizzyturns', true) == "on") { ?> checked="checked"<?php } ?>  />Dizzy Turns</label>
        <label><input type="checkbox" name="breathlessness" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'breathlessness', true) == "on") { ?> checked="checked"<?php } ?>  />Breathlessness or more easily tired than team-mates</label>
        <label><input type="checkbox" name="bloodpressure" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'bloodpressure', true) == "on") { ?> checked="checked"<?php } ?>  />History of high blood pressure</label>
        </div>
        <div>
        <label><input type="checkbox" name="diabetes" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'diabetes', true) == "on") { ?> checked="checked"<?php } ?>  />Diabetes</label>
        <label><input type="checkbox" name="palpitations" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'palpitations', true) == "on") { ?> checked="checked"<?php } ?>  />Palpitations</label>
        <label><input type="checkbox" name="chestpain" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'chestpain', true) == "on") { ?> checked="checked"<?php } ?>  />Chest Pain or Tightness</label>
        <label><input type="checkbox" name="suddendeath" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'suddendeath', true) == "on") { ?> checked="checked"<?php } ?>  />Sudden death in immediate family of anyone under 50</label>
        </div>
        <div>
        <label><input type="checkbox" id="smoking" name="smoking" <?php if ( $disabled ) { ?> disabled='true'<?php } if ( get_post_meta($form_id, 'smoking', true) == "on") { ?> checked="checked"<?php } ?>  />Smoking </label>
        </div>
        <div id="howmanycigs"<?php if ( $current_form->have_posts() && get_post_meta($form_id, 'smoking', true) == "On") { ?> style="display:block"<?php } ?>>
            <label class="smalllabel" for="howmanycigsperday">How many cigarettes do you smoke per day?</label>
            <input type="text" class="smalltextbox notempty" name="howmanycigsperday" id="weight"<?php if ( $disabled ) { ?> disabled='true'<?php } if ( $current_form->have_posts() ) { ?> value='<?php echo get_post_meta($form_id, 'howmanycigsperday', true) ?>'<?php } ?> />
        </div>
    </fieldset>

    <fieldset>
        <legend>Referral Information</legend>
        <div>
            <label for="howdidyouhear">How did you hear about The Bisons?</label>
            <textarea class='notempty' name="howdidyouhear" id="howdidyouhear"<?php if ( $disabled ) { ?> disabled='true'<?php } ?>><?php if ( $current_form->have_posts() ) { echo get_post_meta($form_id, 'howdidyouhear', true); } ?></textarea>
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
                ?><p class='inlineerror'>Manual payment (Not yet verified)</p>
                  <p><em>Please note that a committee member will need to verify your payment before you can be upgraded to full player status. You will receive a receipt by email once this has been done.</em></p><?php
                break; 
                case 2: 
                ?><p class='formoutputlabel paymentreceived'>Manual payment (verified - payment received, thanks!)</p><?php
                break;             
                case 3: 
                ?><p class='formoutputlabel paymentreceived'>Online payment (Payment received, thanks!)</p><?php
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
        <input type='hidden' id='already' name='already' value='Yes' />
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
        
        <div id="inputcarddetails" class="fieldgroup">
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
                <select class="norightmargin data-stripe="exp-month">
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
                
                <select class="norightmargin data-stripe="exp-year">
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

