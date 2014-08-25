<?php
if (!INCLUDED) exit;
$_POST['confirm_action'] = 'true';
$users = explode ( ',', $_POST['email_to']);
$results = send_mandrill_template ( $users, 'generic-email', array('body' => wpautop( stripslashes( $_POST['message_body']) ) ), false, $_POST['message_subject'] );
$sentcount = 0;
$invalidcount = 0;
$rejected = array();
foreach ( $results as $result )
{
    switch ( $result['status'] )
    {
        case 'sent': $sentcount++; break;
        case 'rejected': $rejected[ $result['email'] ] = $result['reject_reason']; break;
        case 'invalid': $invalidcount++; break;
    }
}

if ( $sentcount > 0 )
{
    function bulk_email_update_notice() 
    {
        echo '<div class="updated">';
        
        if ( $sentcount == 1 )
        {
            echo "<p>Email sent successfully.</p>";    
        }
        else 
        {
            echo "<p>Email sent successfully to $sentcount users.</p>";    
        }
        
        echo '</div>';
    }

    add_action('admin_notices', 'bulk_email_update_notice');
}

if ( sizeof ( $rejected ) > 0 )
{
    function bulk_email_error_notice()
    {
        echo '<div class="updated">';
        echo '<p>'.sizeof ( $rejected ).' emails were rejected</p>';
        foreach ( $rejected as $email => $reason )
        {
            switch ( $reason )
            {
                case 'hard-bounce': echo "<p>Email sent to $email received a <a href='http://kb.mailchimp.com/article/whats-the-difference-between-hard-and-soft-bounce-backs/#types'>hard bounce</a>.</p>"; break;
                case 'soft-bounce': echo "<p>Email sent to $email received a <a href='http://kb.mailchimp.com/article/whats-the-difference-between-hard-and-soft-bounce-backs/#types'>soft bounce</a>.</p>"; break;
                case 'spam': echo "<p>Email sent to $email was rejected as spam.</p>"; break;
                case 'unsub': echo "<p>Email sent to $email was rejected because the user has asked to unsubscribe to your emails.</p>"; break;
                case 'custom': echo "<p>Email sent to $email was rejected for an unknown reason.</p>"; break;
                case 'invalid-sender': echo "<p>Email sent to $email was rejected because the sender address was invalid"; break;
                case 'invalid': echo "<p>Email sent to $email was rejected because the message format was invalid.</p>"; break;
                case 'test-mode-limit': echo "<p>Email sent to $email was rejected for an unknown reason.</p>"; break;
                case 'rule': echo "<p>Email sent to $email was rejected for an unknown reason.</p>"; break;
            }
        }
        echo '</div>';
    }
    add_action('admin_notices', 'bulk_email_error_notice');
}

