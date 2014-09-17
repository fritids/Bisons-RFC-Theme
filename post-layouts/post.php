<div itemscope itemytype="http://schema.org/Article" <?php post_class('post') ?>>
      <header>
          <h2><a href="<?php the_permalink() ?>"><span itemprop="name"><?php the_title(); ?></span></a></h2>
          <p><span itemprop="author" itemscope itemtype="http://schema.org/Person"><span span itemprop="name" class="authorsmall"><?php echo get_the_author(); ?></span></span><span itemprop="datePublished" content="<?php the_time("Y-m-d") ?>" class="timesmall"><?php the_time('g:ia') ?></span><span class="datesmall"><?php the_time('jS \o\f F Y') ?></span><?php if (comments_open( get_the_id() )) {?><span><a class="commentsmall" href="<?php the_permalink() ?>#comments"><?php comments_number(' 0',' 1',' %'); ?></a></span><?php } ?><span><?php edit_post_link( 'Edit', ''); ?></span></p>
      </header>
      
      <?php if ( has_post_thumbnail() ) { the_post_thumbnail(); }

      if ( is_single() ) the_content(); 
      else echo preg_replace("/<img(.*?)>/si", "", get_the_excerpt());
      comments_template(); ?>
</div>