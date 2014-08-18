<?php function my_login_logo() { ?>

<style type="text/css">
      body.login div#login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/pinkbisonsvg.svg);
            padding-bottom: 30px;
            padding-bottom: 30px;
            background-size: 150px;
            width: 150px;
            height: 100px;
      }
</style>

<?php } add_action( 'login_enqueue_scripts', 'my_login_logo' );