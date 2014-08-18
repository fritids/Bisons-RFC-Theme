<?php

wp_enqueue_script( 'email_log_js' );


    $args = array (
     'post_type' => 'email_log',
     'posts_per_page' => 20,
     'orderby'   => 'date',
     );
     
     if ( $_GET['id'] )
     {
         $args['meta_key'] = 'user_id';
         $args['meta_value'] = $_GET['id'];  
     }
  $emails = new WP_Query ( $args );
     ?>
 <style type="text/css">
     .emailcontent
     {
         display:none;
     }
 </style>
<div class="wrap">
    <h1>Email Log</h1>
    <?php if ($emails->have_posts() ) : ?>
    <table class="widefat">
        <thead>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Subject</th>
                    <th>Content</th>
                </tr>
            </thead>
        </thead>
        <tbody>
            <?php while ($emails->have_posts()) : $emails->the_post() ?>
            <tr>
                <td><?php $data = get_userdata( get_post_meta(get_the_id(), 'user_id', true ) ); echo $data->display_name ? $data->display_name : get_post_meta(get_the_id(), 'name', true );  ?></td>
                <td><?php echo $data->user_email ? $data->user_email : get_post_meta(get_the_id(), 'email', true ); ?></td>
                <td><?php the_time('g:ia') ?></td>
                <td><?php the_time('jS \o\f F Y') ?></td>
                <td><?php echo get_post_meta(get_the_id(), 'subject', true ) ?></td>
                <td><a href='#' class='showlink'>Show</a>
                    <div class="emailcontent">
                    <?php the_content() ?>
                    </div>
                </td>
            </tr>
            <?php endwhile ?>
        </tbody>
    </table>
    <?php endif ?>
</div>

