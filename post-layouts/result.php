<?php
$parent = get_post_meta( get_the_id(), 'parent-fixture', true);
$fixdate = get_post_meta( $parent, 'fixture-date', true );
$fixdate = date('jS \o\f F Y', $fixdate); 
$opposing = get_post_meta( $parent, 'fixture-opposing-team', true );
$opplink = get_post_meta( $parent, 'fixture-opposing-team-website-url', true );
$ourscore = get_post_meta( get_the_id(), 'our-score', true );
$theirscore = get_post_meta( get_the_id(), 'their-score', true );
?>
<div <?php post_class('post') ?>>
      <header>
          <h2><a href="<?php the_permalink() ?>">Match Result (<?php echo $opposing; ?>)</a></h2>
          <p><span class="authorsmall"><?php echo get_the_author(); ?></span><span class="timesmall"><?php the_time('g:ia') ?></span><span class="datesmall"><?php the_time('jS \o\f F Y') ?></span><?php if (comments_open( get_the_id() )) {?><span><a class="commentsmall" href="<?php the_permalink() ?>#comments"><?php comments_number(' 0',' 1',' %'); ?></a></span><?php } ?><span><?php edit_post_link( 'Edit', ''); ?></span></p>
      </header>
      <p>The results for the match played on the <?php echo $fixdate; ?> are now in!</p>
      <table class="resultstable">
            <tr><td class="left-result">Bristol Bisons RFC</td><td class="scorecell"><?php echo $ourscore; ?></td><td class="scorecell"><?php echo  $theirscore ?></td><td class="right-result"><?php echo team_link($opposing, $opplink); ?>	</td></tr>
      </table>
      <?php comments_template(); ?>
</div>