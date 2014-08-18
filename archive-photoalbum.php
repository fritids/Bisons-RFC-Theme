<?php
get_header();
?>
<div id="wrapper">
    <div id="pagecol">
        <div class='page'>       
			<header>
			    <h2><a href="<?php the_permalink() ?>">Photo Album</a></h2>
			    <p>Courtesy of <a href='http://www.flickr.com/'>Flickr</a></p>
			</header>
			
			<?php $flickr_pages_query = new WP_Query( array ( 'post_type' => 'photoalbum', 'posts_per_page' => 10 ) );
			
			if ( $flickr_pages_query->have_posts() ) : ?>
				<table class="photosets">
					<tbody>
					<?php while ( $flickr_pages_query->have_posts() ) : $flickr_pages_query->the_post(); 
					$setid = get_post_meta ( get_the_id(), 'setid' );
					
					?>
						<tr>
						    <td class="photosetsThumbs"><a href='<?php the_permalink() ?>'><img src='<?php echo get_post_meta ( get_the_id(), 'primary_photo_url' ); ?>' /></a></td>
						    <td><h3><a href='<?php the_permalink() ?>'><?php echo $title ?></a></h3>
						        <ul class="metalist">
						            <?php if ($description) : ?><li><strong>Description</strong><br /><?php echo $description ?></li><?php endif ?>
						            <li><strong>Date Created</strong><br /><?php echo $created ?></li>
						        </li>
						    </td>
						</tr>    
					<?php endwhile; ?>
					</tbody>
				</table>
			<?php endif; ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>