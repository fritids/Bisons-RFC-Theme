<?php
function prepare_pages( $query )
{
    
    if ( $query->is_main_query() )
    {
        if ( ! is_object ( $query ) ) 
            return false;
        
        switch ( $query->query['post_type'] )
        {
       
            case 'player-page': 
                if( file_exists( dirname( __FILE__  ) . '/../prep_player_pages/' .  $query->query['name'] . '.php' ) )
                   include_once( dirname( __FILE__  ) . '/../prep_player_pages/' . $query->query['name'] . '.php' ); 
            break;
            
        }
    }
}
add_action ( 'pre_get_posts', 'prepare_pages' );
