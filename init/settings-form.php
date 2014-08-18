<h2>Bisons Online Settings</h2>
<form method="POST" action="options.php">
    <?php 
    settings_fields ( 'bisons-settings' );
    do_settings_sections( 'bisons-settings' );
    submit_button( );
    ?>
</form>
