<?php

//Log a user in and set them as current user
function my_user_login($username,$password)
{
	$creds = array();
	$creds['user_login'] = $username;
	$creds['user_password'] = $password;
	$creds['remember'] = true;
	$user = wp_signon( $creds, false );
	wp_set_current_user( $user->ID ); //Here is where we update the global user variables
    wp_set_auth_cookie( $user->ID  );

	return $user;
}
