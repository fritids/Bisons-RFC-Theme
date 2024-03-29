<?php

// Create 'every ten minutes' option
add_filter( 'cron_schedules', 'bisons_add_ten_minute_schedule' ); 
function bisons_add_ten_minute_schedule( $schedules )
{
  $schedules['everyten'] = array(
    'interval' => 10 * 60, //7 days * 24 hours * 60 minutes * 60 seconds
    'display' => __( 'Every Ten Minutes', 'bisonsrfc' )
  );
  
  return $schedules;
}

// Active schedule on theme load
add_action('after_switch_theme', 'create_regular_album_download_schedule');
function create_regular_album_download_schedule()

{
    $timestamp = wp_next_scheduled ( 'create_regular_album_download' );
    if ( ! $timestamp )
    { wp_schedule_event( time(), 'everyten', 'create_regular_album_download' );}
}
add_action( 'create_regular_album_download', 'download_albums' );

function download_albums()
{

    $flickr = new Flikr( $GLOBALS['api_settings'], 'json', false, 0);
        
    $options = get_option('social-media-settings-page');
    $flickrusername = $options['flickr-username'];
    $userid = $flickr->peopleFindByUsername ( $flickrusername )->user->nsid;
    
    
    // Get existing sets
    $flickr_pages_query = new WP_Query( array ( 'post_type' => 'photos', 'posts_per_page' => -1 ) );
    $existingsets = array();
    while ( $flickr_pages_query->have_posts() ) 
    {
            $flickr_pages_query->the_post();
    
        // Build an array containing the post IDs and update dates for comparisons
        $id = get_post_meta ( get_the_id(), 'setid', true ); 
        
        
        
        $existingsets[ $id ] = array 
        (
            'postid'                => get_the_id ( $id ),
            'flickr_updated'        => get_post_meta ( get_the_id(), 'flickr_updated', true ),
        );
    }
    

    // Get remote setlists
    $lists = $flickr->photosetsGetList( $userid, false, false, 'url_q' )->photosets->photoset;
       
    // For each of the remote lists, check if there is a currently existing set. If not, create it    
    foreach ($lists as $list)
    {
        $post_details = array
        (
                'post_title'        => $list->title->_content,
                'post_status'       => 'publish',
                'post_type'         => 'photos',
                'post_date'         => date ( 'Y-m-d H:i:s', $list->date_create ),
                'post_content'      => $list->description->_content
        );
        
        
        
        if (! $existingsets [ $list->id ] )
        {       
            $postid = wp_insert_post ( $post_details );
            $id = 
            update_post_meta( $postid, 'setid', $list->id );
            update_post_meta( $postid, 'primary_photo_url', $list->primary_photo_extras->url_q );
            update_post_meta( $postid, 'description', $list->description->_content );
        
            update_post_meta( $postid, 'flickr_updated', $list->date_update );
            
        // If there is one but the update date is different, update the post
        } else if ( $existingsets [ $list->id ]['flickr_updated'] != $list->date_update )
        
        {
            $post_details['ID'] = $existingsets [ $list->id ]['postid'];
            wp_update_post ( $post_details );
            update_post_meta( $post_details['ID'], 'primary_photo_url', $list->primary_photo_extras->url_q );
            update_post_meta( $post_details['ID'], 'flickr_updated', $list->date_update );
            update_post_meta( $postid, 'description', $list->description->_content );
        }
    }

}

