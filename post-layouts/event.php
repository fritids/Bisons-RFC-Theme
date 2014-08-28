<?php
$eventdate = get_post_meta(get_the_id(), 'date', true );
$date = date('jS \o\f F Y', $eventdate);
$isodate = date('c', $eventdate);
$enddate = get_post_meta( get_the_id(), 'enddate', true ) ? date( 'jS \o\f F Y' , (int) get_post_meta( get_the_id(), 'enddate', true ) ) : null;
$time = get_post_meta(get_the_id(), 'time', true ) ? reformat_date(get_post_meta(get_the_id(), 'time', true ), 'g:ia') :  false; 
$endtime = get_post_meta(get_the_id(), 'endtime', true );
$address = get_post_meta(get_the_id(), 'address', true );
$fbevent = get_post_meta(get_the_id(), 'facebook-event', true );
$description = get_post_meta(get_the_id(), 'description', true );
$image_id = get_post_meta(get_the_id(), 'image_id', true );
$image_url = wp_get_attachment_url( $image_id );

?>
<div itemscope itemtype="http://data-vocabulary.org/Event" <?php post_class('post') ?>>
      <header>
          <h2><a itemprop="url" href="<?php the_permalink() ?>"><span itemprop="summary"><?php the_title(); ?></span></a></h2>
          <p><span class="authorsmall"><?php echo get_the_author(); ?></span><span class="timesmall"><?php the_time('g:ia') ?></span><span class="datesmall"><?php the_time('jS \o\f F Y') ?></span><?php if (comments_open( get_the_id() )) {?><span><a class="commentsmall" href="<?php the_permalink() ?>#comments"><?php comments_number(' 0',' 1',' %'); ?></a></span><?php } ?><span><?php edit_post_link( 'Edit', ''); ?></span></p>
      </header>

      <div class="feature-image" href='<?php echo $image_url; ?>'>
            <img itemprop="photo"  class='alignright<?php if (get_post_meta(get_the_id(), 'whitebackground', true )) echo " noborder";  ?>' src="<?php echo $image_url; ?>" alt="image <?php echo $image_id ?>"/>
            <ul>
                <li class="strong">Event Details</li>
            	<?php echo datetime_string ( $date, $enddate, $time, $endtime, false, $isodate ) ?>
            	<li><h4 class='addresssmall'>Location</h4><?php echo str_replace ("\n", '<br />', $address) ?></li>
			<?php if($fbevent) : ?><li class="facebooksmall"><a href='<?php echo $fbevent; ?>'>Facebook</a></li><?php endif ?>
                  
            </ul>
      </div>
      <span itemprop="description"><?php if ( is_single() ) the_content(); else the_excerpt(); ?></span>
      <?php comments_template(); ?>
</div>