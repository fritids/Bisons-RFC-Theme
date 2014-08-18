<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol" class="ajaxcol">
        <div class='page'>       
            <header>
                <h2>Login</h2>
            </header>
            <p>To access that page you need to be logged in as a committee member. Enter your username and password in the login box below, or use the menu above to return to the homepage. </p>
            <form name="loginform" id="loginform" action="<?php echo get_site_url(); ?>/wp-login.php" method="post">
                <div>
                    <label for="user_login">username</label>
                    <input type="text" name="log" id="user_login" class="input" value="" size="20" />
                </div>
                <div>
                    <label for="user_pass">password</label>
                    <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" />
                </div>
                <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI'] ?>" />
                <input type="submit" value="Log In" name="wp-submit" id="wp-submit">
            </form>
        </div>
    </div>
</div>
<?php get_footer(); ?>