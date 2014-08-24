<?php
$formsTable = new Membership_Forms_Table(); 
$formsTable->prepare_items();
?>
<div class="wrap">
      <h2>Players <a class='add-new-h2' href='<?php echo admin_url( 'admin.php?page=add-player' ) ?>'>Add Player</a></h2>
      <p>The table below contains all the membership forms that have been submitted via the website this season. If it is hard to read because of the number of columns, you can turn some of them off - just click on 'screen options' (look at the top right hand corner) and choose the columns you want to see.</p>
      <?php
      
      if ( $_POST ) 
      {
          switch ( $_POST['action'] )
          {
              case 'bulk_email':        
                  
                  echo "<h2>Send Email</h2><p>Emails sent via this form will be sent as HTML email with the Mandrill generic email template.</p>";
                  $emails = array();
                  $user_ids = array();
                  foreach ( $_POST['id'] as $id )
                  {
                        $user_id = get_post_field( 'post_author', $id );
                        $user_ids[] = $user_id;
                        $membership_form = new WP_Query ( array (
                            'post_type' => 'membership_form',
                            'posts_per_page' => 1,
                            'orderby'   => 'date',
                            'order'     => 'ASC',
                            'author'   => $user_id,
                        ) );
                        
                        
                        
                        while ( $membership_form->have_posts() )
                        {
                            $membership_form->the_post();
                            $name = get_post_meta(get_the_id(), 'firstname', true).' '.get_post_meta(get_the_id(), 'surname', true);    
                            $email = get_post_meta(get_the_id(), 'email_addy', true);
                            $emailstring = "$name &lt;$email&gt;";
                        }
                        $emails[] = $emailstring;
                  }
                  
                  $emails = implode ( ', ', $emails);
                  $emailform = new Wordpress_Form ( null, null, 'post', 'Send', 'emailform' );
                  $emailform->not_using_fieldsets();
                  $emailform->add_inner_tag ( 'div');
                  $emailform->add_inner_tag ( 'table', 'form-table' );
                  $emailform->add_inner_tag ( 'tbody' );
                  $emailform->set_label_parent_tag ( 'th' );
                  $emailform->set_row_tag ( 'tr' );
                  $emailform->set_field_parent_tag ( 'td' );
                  $emailform->set_submit_button_classes ( array ( 'button', 'button-primary', 'button-large') );
                  $emailform->add_static_text ( null, 'recipients', 'Recipient(s)', false, false, $emails );
                  $emailform->add_hidden_field ( null, 'email_to', implode ( ',', $user_ids ) );
                  $emailform->add_hidden_field ( null, 'action', 'bulk_email_submit' );
                  $emailform->add_text_input(null, 'message_subject', 'Subject', 'regular-text');
                  $emailform->add_textarea( null, 'message_body', 'Body', 'large-text' );
                  $emailform->form_output ( ); 
              break;
                            

      }
      }
      ?>
      <form method="post">
    <?php 
    $formsTable->display(); 
      ?>
      </form>
</div>