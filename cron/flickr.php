<?php

include_once( dirname( __FILE__ ) . '/../API_Wrapper/flikr.php' );
include_once( dirname( __FILE__ ) . '/../init/settings.php' );
include_once( dirname( __FILE__ ) . '/../../../../wp-load.php');
include_once( dirname( __FILE__ ) . '/../dBug.php' );


$flickr = new Flikr( $GLOBALS['api_settings'] );
$options = get_option('social-media-settings-page');
$flickrusername = $options['flickr-username'];
$userid = $flickr->peopleFindByUsername ( $flickrusername )->user->nsid;

// Get existing sets
$flickr_pages_query = new WP_Query( array ( 'post_type' => 'photoalbum', 'posts_per_page' => -1 ) );
$existingsets = array();
while ( $flickr_pages_query->have_posts() ) {
		$flickr_pages_query->the_post();

	// Build an array containing the post IDs and update dates for comparisons
	$existingsets[ get_post_meta ( get_the_id(), 'setid' )[0] ] = array 
	(
		'postid'				=> get_the_id ( ),
		'flickr_updated'		=> get_post_meta ( get_the_id(), 'flickr_updated' ),
	);
}


// Get remote setlists
$lists = $flickr->photosetsGetList( $userid, false, false, 'url_q' )->photosets->photoset;

// For each of the remote lists, check if there is a currently existing set. If not, create it
foreach ($lists as $list)
{
	$post_details = array
	(
			'post_title'  		=> $list->title->_content,
			'post_status' 		=> 'publish',
			'post_type'   		=> 'photoalbum',
			'post_date'			=> date ( 'Y-m-d H:i:s', $list->date_create ),
			'post_content'		=> $list->description->_content
	);
    
	if (! $existingsets [ $list->id ] )
	{		
		$postid = wp_insert_post ( $post_details );
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
