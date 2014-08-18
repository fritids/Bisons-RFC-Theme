<?php
$postdate = get_post_meta( get_the_id(), 'fixture-date', true );
$textdate = get_post_meta( get_the_id(), 'text-date', true );
$kickoff = get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) ? get_post_meta( get_the_id(), 'fixture-kickoff-time', true ) : 'TBC';
$kickoffexplode = explode ( ':', $kickoff);
$hour = $kickoffexplode[0];
$minute = $kickoffexplode[1];
$kickoff = reformat_date($kickoff, 'g:ia');
$fixdate = date('jS \o\f F Y', (int) $postdate);
$isodate = mktime ( (int) $hour, (int) $minute, 0, date('n', (int) $postdate), date('j', (int) $postdate), date('Y', (int) $postdate) );
$isodate = date('c', $isodate);
$oppteam = get_post_meta( get_the_id(), 'fixture-opposing-team', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team', true ) : 'TBC';
$opplink = get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) ? get_post_meta( get_the_id(), 'fixture-opposing-team-website-url', true ) : false;

$playtme = get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) ? get_post_meta( get_the_id(), 'fixture-player-arrival-time', true ) : false;
$playtme = reformat_date($playtme, 'g:ia');
$address = wpautop ( get_post_meta( get_the_id(), 'fixture-address', true ) ? get_post_meta( get_the_id(), 'fixture-address', true ) : 'TBC' );
$gmpcode = get_post_meta( get_the_id(), 'fixture-gmap', true ) ? get_post_meta( get_the_id(), 'fixture-gmap', true ) : false;
$fixface = get_post_meta( get_the_id(), 'fixture-facebook-event', true ) ? get_post_meta( get_the_id(), 'fixture-facebook-event', true ) : false;
$gmpcode = html_entity_decode($gmpcode);
?>
<div itemscope itemtype="http://data-vocabulary.org/Event" <?php post_class('post') ?>>
      <header>
          <h2><a itemprop="url" href="<?php the_permalink() ?>"><span itemprop="summary"><?php the_title(); ?></span></a></h2>
          <p><span class="authorsmall"><?php echo get_the_author(); ?></span><span class="timesmall"><?php the_time('g:ia') ?></span><span class="datesmall"><?php the_time('jS \o\f F Y') ?></span><?php if (comments_open( get_the_id() )) {?><span><a class="commentsmall" href="<?php the_permalink() ?>#comments"><?php comments_number(' 0',' 1',' %'); ?></a></span><?php } ?><span><?php edit_post_link( 'Edit', ''); ?></span></p>
      </header>

      <p><span itemprop="eventType">Fixture</span> details have been confirmed! Friends and supporters are always welcome to our matches so let us know if you want to come along. <?php echo $fixface ? "For more details, try the  <a href='$fixface' title='Facebook event page'>Facebook event page</a> for this fixture." : ''; ?></p>
      <ul class="metalist">
          <li class="date">
                <?php if ( $textdate ) : echo $textdate; ?>
                <?php else : ?><time itemProp="startDate" datetime="<?php echo $isodate ?>"><?php echo $fixdate; ?></time><?php endif ?>
          </li>
          <li class="info">Playing against <?php echo link_if_avail($oppteam, $opplink) ?></li> 
          <li class="info">Kickoff is at <strong><?php echo $kickoff; ?></strong>, players please arrive for <strong><?php echo $playtme; ?></strong></li>
          <li class="address"><span itemprop="location" class="gmap-address map-<?php the_id(); ?>"><?php echo $address; ?></span></li>
          <div class='gmap-border'><div class="gmap-canvas" id="map-<?php the_id(); ?>"></div></div>
      </ul>

      <?php comments_template(); ?>
</div>

