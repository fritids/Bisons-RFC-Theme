<?php if ( ! current_user_can ('view_committee_area') ) { header('Location: ' . wp_login_url( "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ) ); } ?>


<?php get_header(); ?>
<div id="wrapper">
    <div id="pagecol">
        <div class='page'>       
    <header>
            <h2>Committee area</h2>
            <p><a href='/wp-admin/post-new.php?post_type=committee-page'>New committee page</a></p>
    </header>
            <p>Welcome to the committee area wiki. This page is only accessible if you are logged in as a committee member.</p>
            <?php 
            $page_groups = get_categories( array(
                    'taxonomy' => 'committee-page-groups')
            );
            foreach($page_groups as $group) { ?>
                <h3><?php echo $group->name; ?></h3>
                <table>
                    <body>

                    <?php $page_list = new WP_Query(array(
                        'post_type' => 'committee-page',
                        'paged' => -1,
                        'committee-page-groups' => $group->slug

                    ));
                    
           

                    while($page_list->have_posts()) :
                        $page_list->the_post();
                        $post = get_post();
                        ?> <tr>
                        <td class="left-col"><a href='<?php echo get_permalink(); ?>' title='<?php echo get_the_title(); ?>'><?php echo get_the_title(); ?></a></td>
                        <?php if (get_post_meta(get_the_id(), 'description', true)) : ?>
                        <td><?php echo get_post_meta(get_the_id(), 'description', true) ?></td>
                        <?php endif; ?>
                        <?php if( get_edit_post_link( get_the_id() )  ) { ?>
                        <td <?php if (! get_post_meta(get_the_id(), 'description', true)) echo 'colspan="2" '; ?>class='narrowcell'><a class="edit-link" href="<?php echo get_edit_post_link( get_the_id() ); ?>">edit</a></td><?php } ?>

                    </tr>
                    <?php endwhile ?>
                    </tbody></table> 
            <?php } ?>

        </div>
    </div>
</div>

<?php get_footer(); ?>