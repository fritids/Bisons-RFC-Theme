<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class="ajaxcol">
        <div class='page'>       
            <header>
                <h2>Player's Area</h2>
            </header>
            <?php 


            if ( wp_verify_nonce( $_POST['nonce'], 'wordpress_form_submit' ) && 
                ( $userid = my_user_login ( $_POST['player_username'], $_POST['player_password'] ) ) )
            {

                  
            }
		else
		{

                  $loginform = new Wordpress_Form ( null, null, 'post', 'Login', 'loginbox' );
                  $loginform->add_fieldset( 'login', 'Login');
                  $loginform->add_text_input ( 'login', 'player_username', 'Username', 'notempty');
                  $loginform->add_text_input ( 'login', 'player_password', 'Password', 'notempty');
                  $loginform->form_output();
            } ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>