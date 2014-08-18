<header>
    <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
        <?php if ( current_user_can('edit_post') ) { ?><p><?php edit_post_link( 'Edit page'); ?></p><?php } ?>
</header>

<?php 
the_content(''); 
comments_template();
?>
   