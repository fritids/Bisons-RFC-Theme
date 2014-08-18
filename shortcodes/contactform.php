<?php
function contact_form_shortcode ()
{
      wp_enqueue_script ('formscripts');
      
      $emailopt = get_option('email-settings-page');


          
      $contactform = new Wordpress_Form ( null, null, 'post', 'Submit', 'contactform' );
      $contactform->add_fieldset( 'contact', 'Contact Us');
      
      $types = array ();
      
      if ( $emailopt['contact-us-email-query-type-1'] && $emailopt['contact-us-email-address-1'] )
            $types[1] = $emailopt['contact-us-email-query-type-1'];
      if ( $emailopt['contact-us-email-query-type-2'] && $emailopt['contact-us-email-address-2'] )
            $types[2] = $emailopt['contact-us-email-query-type-2'];
      if ( $emailopt['contact-us-email-query-type-3'] && $emailopt['contact-us-email-address-3'] )
            $types[3] = $emailopt['contact-us-email-query-type-3'];
      
      if ( sizeof ( $types ) )
      	$contactform->add_list_box('contact', 'query_type', 'What is your question about?', $types  );
      
      $contactform->add_text_input('contact', 'prospective_player_name', 'Name');
      $contactform->add_text_input('contact', 'prospective_player_email', 'Email');
	$contactform->add_text_input('contact', 'message_subject', 'Subject');
      $contactform->add_textarea('contact', 'message_body', 'Body');
      $contactform->add_captcha('contact', 'captcha', 'CAPTCHA', get_bloginfo ( 'template_url' ) . '/captcha.php', 'captcha', 'Enter the numbers from blue image into this box to prove you are a human.'); 
 	
      if ( wp_verify_nonce( $_POST['nonce'], 'wordpress_form_submit' ) && isset ( $_POST['prospective_player_email'] ) && ! $contactform->is_errors() )
      {
            
            // Send email to committee
            switch ( $_POST[ 'query_type' ] )
            {
                  case 1: $to = $emailopt['contact-us-email-address-1']; break;
                  case 2: $to = $emailopt['contact-us-email-address-2']; break;
                  case 3: $to = $emailopt['contact-us-email-address-3']; break;
            }
            
            $subject = $_POST['message_subject'];
            $template = wpautop ( $emailopt['contact-us-template'] );
            $message = wpautop ( $_POST['message_body'] );
            $name = $_POST['prospective_player_name'];
            $cc = $emailopt['contact-us-email-address-cc'];
            $message_to_send = preg_replace("/(.*)@@name@@(.*)/", "$1$name$2", $template);
            $message_to_send = preg_replace("/(.*)@@subject@@(.*)/", "$1$subject$2", $message_to_send);
            $message_to_send = preg_replace("/(.*)@@message@@(.*)/", "$1$message$2", $message_to_send);
            if ( $emailopt['email-css-ext'] ) { $message_to_send = '<link rel="stylesheet" href="'.$emailopt['email-css-ext'].'" type="text/css" />'.$message_to_send; }
            if ( $emailopt['email-css'] ) { $message_to_send = '<style type="text/css">'.$emailopt['email-css'].'</style>'.$message_to_send; }

            $headers = "From: $name <".$_POST['prospective_player_email']. ">\r\n";
		$headers .= $cc ? "Cc: <$cc>\r\n" : ''; 
            wp_mail( $to, $subject, $message_to_send, $headers );

            // Copy email to sender
            $emailopt = get_option('email-settings-page');
            $to = $_POST['prospective_player_email'];
            $subject = 'Message received';
            $template = wpautop( $emailopt['contact-us-copy-template'] ) ;
            $message_to_send = preg_replace("/(.*)@@name@@(.*)/", "$1$name$2", $template);
            $message_to_send = preg_replace("/(.*)@@subject@@(.*)/", "$1$subject$2", $message_to_send);
            $message_to_send = preg_replace("/(.*)@@message@@(.*)/", "$1$message$2", $message_to_send);
            if ( $emailopt['email-css-ext'] ) { $message_to_send = '<link rel="stylesheet" href="'.$emailopt['email-css-ext'].'" type="text/css" />'.$message_to_send; }
            if ( $emailopt['email-css'] ) { $message_to_send = '<style type="text/css">'.$emailopt['email-css'].'</style>'.$message_to_send; }

            $headers = 'From: Bristol Bisons RFC <noreply@bisonsrfc.co.uk>' . "\r\n";
            wp_mail( $to, $subject, $message_to_send, $headers );
      }
      
      return $contactform->form_output ( false ); 
}
add_shortcode('contactform', 'contact_form_shortcode');
