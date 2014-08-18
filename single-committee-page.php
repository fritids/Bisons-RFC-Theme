<?php if ( ! current_user_can ('view_committee_area') ) { header('Location: ' . wp_login_url( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) ); } 


$post = $wp_query->get_queried_object();
$pagename = $post->post_name;
 ?>

<?php get_header(); ?>

<div id="wrapper">

    <div id="pagecol">
        <div class='page'>       
        <?php 
            if( file_exists( dirname( __FILE__  ) . '/hardcodedcommittepages/' . $pagename . '.php' ) ) :
                    get_template_part( 'hardcodedcommittepages/' . $pagename );
            else : 

                if ( have_posts() ) : ?>
                <?php while (have_posts() ) : the_post(); ?>
                <header>
                    <h2><?php the_title(); ?></h2>
                    <p>Page created on the <?php the_time('jS \o\f F Y') ?> by <?php echo get_the_author(); ?> - <a href='/committee-area/'>Committee area</a><?php edit_post_link( 'Edit page', ' - '); ?></p>

                </header>
                <?php the_content(); ?>
                <?php endwhile; ?>
            <?php else : ?>
            <h2>Nothing Found</h2>
            <p>Sorry, but the content you are looking for isn't here...</p>
            <p><a href="<?php echo get_option('home'); ?>">Return to the homepage</a></p>
            <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>

