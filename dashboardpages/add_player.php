<?php



if ( wp_verify_nonce( $_POST['nonce'], 'wordpress_form_submit' ) )
{
      
	if ( $_POST['email'] == '' ) $error = "Please enter an email address...";
      if ( $_POST['surname'] == '' ) $error = "Please enter a surname...";
      if ( $_POST['firstname'] == '' ) $error = "Please enter a first name...";

      if ( email_exists ( $_POST['email'] ) ) $error = "That email address already exists on our database. Try another one...";
      if ( $_POST['username'] != '' && username_exists ( $_POST['username'] ) ) $error = "That username already exists on our database. Try another one...";
      
      if ( ! $error )
      {
            // Generate a password
            $password = $_POST['password'] ? $_POST['password'] : wp_generate_password ( 8, false, false );
            
            // Generate a unique username
            $baseuser = strtolower ( preg_replace ('/[^\w\d]/ui', '', $_POST['firstname'] ).preg_replace('/[^\w\d]/ui', '', $_POST['surname'] ) );
            $username = $baseuser; 
            for ($i = 1; username_exists ( $username ); $i++ ) $username = $baseuser.$i;
            
            // Create the user
            $user_id = wp_insert_user ( array ( 'user_login' => $username,
                                                'user_pass' => $password,
                                                'user_email' => $_POST['email'],
                  					'nickname' => $_POST['firstname']." ". $_POST['surname'],
                                                'first_name' => $_POST['firstname'], 
                                                'last_name' => $_POST['surname'] ) );
            
            // Assign roles
            $user = new WP_User ( $user_id );
            $user->set_role ('guest_player');
            
            // Prepare email data
            $emailopt = get_option('email-settings-page');
		$data = array (
                  'username' => $username,
                  'password' => $password,
                  'memsecretary' => $emailopt['email-memsec']
            );
            
            // Send out Mandrill template via API library
            send_mandrill_template ( $user_id, 'welcome-email', $data, 'registration' );
            $formsubmitted = true;
      }
} 

$form = new Wordpress_Form ( null, null, 'post', 'Create', 'add_player' );

$form->not_using_fieldsets();
$form->add_inner_tag ( 'div', null, 'custom-form' );
$form->add_inner_tag ( 'table', 'form-table' );
$form->add_inner_tag ( 'tbody' );
$form->set_row_tag ( 'tr' );
$form->set_label_parent_tag ( 'th' );
$form->set_field_parent_tag ( 'td' );
$form->set_forminfo_tag ('span', 'description');
$form->set_submit_button_classes ( array ( 'button', 'button-primary', 'button-large') );;

$form->add_text_input ( null, 'firstname', 'First Name', 'notempty', null, $error ? $_POST['firstname'] : '' );
$form->add_text_input ( null, 'surname', 'Surname', 'notempty', null, $error ? $_POST['surname'] : '' );
$form->add_text_input ( null, 'email', 'Email Address', 'notempty', null, $error ? $_POST['email'] : '' );
$form->add_text_input ( null, 'username', 'Username', null, '<strong>This field is optional</strong>. If you don\'t fill it in the username will be generated automatically.', $error ? $_POST['username'] : '' );
$form->add_password_input ( null, 'password', 'Password', null, '<strong>This field is optional</strong>. If you don\'t fill it in the password will be generated automatically.', $error ? $_POST['password'] : '' );
?>

<div class="wrap"> 
<h1>Add Player</h1>
<?php if ( $error ) : ?>
      <div class='error'><p><?php echo $error ?></div>
<?php elseif ( $formsubmitted ) : ?>
      <div class='updated'><p>Player added! They will receive username and password details in their email shortly.</p></div>
<?php endif ?>
<p>Please fill in the form below to add a new user. Note that you MUST provide an email address because the player's username and password will be automatically generated and sent out by email.</p>
<?php $form->form_output() ?>
</div>
