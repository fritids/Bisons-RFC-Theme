<?php if(!$_GET['parent_post'] && $_SERVER['PHP_SELF'] == '/wp-admin/post-new.php') { ?>

<p>Error: You cannot create a match report from here - please edit a fixture and then choose the 'create match report' button.</p>
<?php } else {
    $fixdate = date('jS \o\f F Y', get_post_meta( get_post_meta( $post->ID, 'parent-fixture', true), 'fixture-date', true ));
    $oppteam = get_post_meta( get_post_meta( $post->ID, 'parent-fixture', true), 'fixture-opposing-team', true ); ?>
<p>This match report will be linked in the Wordpress database with the match played against <?php echo $oppteam; ?> on the <?php echo $fixdate; ?>.</p>
<div><input type="hidden" name="parent-fixture" value="<?php echo $_GET['parent_post'] ? $_GET['parent_post'] : get_post_meta( $post->ID, 'parent-fixture', true); ?>" />
</div>
<?php } ?>