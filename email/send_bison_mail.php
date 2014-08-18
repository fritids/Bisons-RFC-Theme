<?php
function send_bison_mail($user, $subject, $content, $tags = false )
{

    // Get email to send to from Wordpress
    $info = get_userdata( $user );
    $email = $info->user_email;
    $firstname = $info->user_firstname;
    $lastname = $info->user_lastname;

    $emailopt = get_option('email-settings-page');

    $mandrill = new Mandrill('ZzbBwttWRHJ41GL4BZmmsQ');
      
    // Add CSS to body then attach to email
    if ( $emailopt['email-css'] ) { $content = '<style type="text/css">'.$emailopt['email-css'].'</style>'.$content; }
    if ( $emailopt['email-css-ext'] ) { $content = '<link rel="stylesheet" href="'.$emailopt['email-css-ext'].'" type="text/css" />'.$content; }

    $message = array(
            'html' => $content,
            'subject' => $subject,
            'from_email' => $emailopt['new-user-email-replyto-address'],
            'from_name' => $emailopt['new-user-email-replyto-name'],
            'to' => array(
                  array(
                        'email' => $email,
                        'name' => "$firstname $lastname",
                        'type' => 'to'
                  )
            ),
            'headers' => array('Reply-To' => $emailopt['new-user-email-replyto-name']),
            'important' => false,
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => false,
            'auto_html' => false,
            'inline_css' => true,
            'url_strip_qs' => false,
            'preserve_recipients' => false,
            'view_content_link' => null,
            'bcc_address' => 'message.bcc_address@example.com',
            'tracking_domain' => 'bisonsrfc.co.uk',
      );
      
	if ( $tags && is_array ( $tags ) )
      {
		$message['tags'] = $tags;
      }
      else if ( $tags )
      {
            $message['tags'] = array ( $tags );
      }
                  

      $async = false;
      $result = $mandrill->messages->send($message, $async);
}


    function send_mandrill_template ( $user, $template, $data, $tags = false, $subject = false )
{
    
    $user = is_array ( $user ) ? $user : array ( $user );
    $to = array();
    
    
    foreach ( $user as $id )
    {
        // Get user information from Wordpress
        $info = get_userdata( $id );
        $email = $info->user_email;
        $firstname = $info->user_firstname;
        $lastname = $info->user_lastname;
        $to[] =  array(
                      'email' => $email,
                      'name' => "$firstname $lastname",
                      'type' => 'to'
                );
    }
    // Get email options
    $emailopt = get_option('email-settings-page');
    
    // Initialise Mandrill  
        $mandrill = new Mandrill('ZzbBwttWRHJ41GL4BZmmsQ');
        
        // Prepare merge variables
        $merge_vars = array(); 
        foreach ($data as $key => $value )
        {
              $merge_vars[] = array (
                    'name'    => $key,
                    'content'  => $value
              );
    }
    

    // Prepare message settings
    $message = array(
        'from_email' => $emailopt['new-user-email-replyto-address'],
        'from_name' => $emailopt['new-user-email-replyto-name'],
        'to' => $to,
        'headers' => array('Reply-To' => $emailopt['new-user-email-replyto-name']),
        'important' => false,
        'track_opens' => true,
        'track_clicks' => true,
        'inline_css' => false,
        'url_strip_qs' => false,
        'preserve_recipients' => false,
        'view_content_link' => true,
        'tracking_domain' => 'bisonsrfc.co.uk',
        'global_merge_vars' => $merge_vars,
        'tags' => array('password-resets')
    );
    
    
    // Add optional settings
    if ( $subject ) 
          $message['subject'] = $subject;
    
    if ( $tags && is_array ( $tags ) )
	$message['tags'] = $tags;
      
    else if ( $tags )
	$message['tags'] = array ( $tags );

      
    $async = false;
    
    try 
    {
      // Submit request
    	$result = $mandrill->messages->sendTemplate($template, $template_content, $message, $async);
    }
    catch ( Mandrill_Error $e )
    {
    	echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
      throw $e;
    }
}
