<?php


function format_timestamp ( $timestamp )
{
      return date (  'Ymd\THis' , $timestamp );
}

function format_datestamp ( $timestamp )
{
      return date (  'Ymd' , $timestamp );
}

function ical_events_feed ()
{
      $ical_events = array();

      // Load posts
      $fixtures = new WP_Query(array(
            'post_type' => 'fixture',
            'nopaging'  => 'true',
            'orderby'   => 'meta_value',
            'meta_key'  => 'fixture-date',
            'order'     => 'ASC'
      ) );


      // Loop through each fixture
      while ( $fixtures->have_posts() ) 
      {
            $fixtures->the_post();

            // Create start and end time from meta fields
            $time = get_post_meta ( get_the_id(), 'fixture-kickoff-time', true );
            $time = explode ( ':', $time );
            $hour = (int) $time[0];
            $minute = (int) $time[1];         
            $date = get_post_meta ( get_the_id(), 'fixture-date', true );
            $day = date ( 'j' , $date);
            $month = date ( 'n' , $date);
            $year = date ( 'Y' , $date );
            $dtstart = mktime ( $hour,  $minute, 0, $month,  $day, $year );             
            $dtend = $dtstart + 6000;

            // Store in array
            $event = array (
                  'UID'		=> md5( get_the_id() ) . "@bisonsrfc.co.uk",
                  'DTSTART'	=> format_timestamp( $dtstart ),
                  'DTEND' 	=> format_timestamp( $dtend ),
                  'CREATED'   => get_the_time ( 'Ymd\THis' ),
                  'SUMMARY'   => get_the_title() . ' (Fixture)',
                  'URL'	=> get_the_permalink()

            );

            // If modified date is different to post date, add to array
            if ( get_the_modified_date() != get_the_time() ) $event['LAST-MODIFIED'] = get_the_modified_date('Ymd\THis');
			
			// Add in the location if there is one
			if ( $location = get_post_meta ( get_the_id(), 'fixture-address', true ) ) $event['LOCATION'] = str_replace("\n", '\n', $location);

            // Add array to master array
            array_push ( $ical_events, $event );
      }

      $events = new WP_Query(array(
          'post_type' => 'event',
          'nopaged'   => 'true',
          'orderby'   => 'meta_value',
          'meta_key'  => 'date',
          'order'     => 'ASC',
      ) );

      while ( $events->have_posts() )
      {
            $events->the_post();

            $startDate = get_post_meta ( get_the_id(), 'date', true );
            $enddate = get_post_meta ( get_the_id(), 'enddate', true );

            // Split the start hour into hours and minutes
            if ( $starttime =  get_post_meta ( get_the_id(), 'time', true ) )
			{
				$starttime = explode ( ':', $starttime );
	            $starttimehour = (int) $starttime[0];
	            $starttimeminute = (int) $starttime[1];
				$starttime = true;
			} 
			else $starttime = false;

            // If there is an end hour, split it into hours and minutes, but if not use the start hour
			
			if ( $endtime =  get_post_meta ( get_the_id(), 'endtime', true ) )
			{
            	  $endtime = explode ( ':', $endtime );
				  $endtimehour = (int) $endtime[0];
				  $endtimeminute = (int) $endtime[1];
				  $endtime = true;
		    }
			else if ( $starttime && ( $startDate == $enddate ) ) 
			{
			 	$endtimehour = $starttimehour;
			  	$endtimeminute = $starttimeminute;
			  	$endtime = false;   
			}
			else if ( $starttime )
			{
				$enddate += 86400;
				$endtimehour = 0;
				$endtimeminute = 0;
			}


            // Split the start date into it's parts and make a unix timestamp out of time and date
            $startDay = date ( 'j' , (int) $startDate );
            $startMonth = date ( 'n' , (int) $startDate );
            $startYear = date ( 'Y' , (int) $startDate );
            $dtstart = mktime ( $starttimehour, $starttimeminute, 0, $startMonth, $startDay, $startYear );             

            // Get the enddate. If it exists, OR an endtime was recorded above
            if ( $enddate || $endtime )
            {
                  $endDay = date ( 'j' , (int) $enddate ? $enddate : $startDate );
                  $endMonth = date ( 'n' , (int) $enddate ? $enddate : $startDate );
                  $endYear = date ( 'Y', (int) $enddate ? $enddate : $startDate );
				  $dtend = mktime ( $endtimehour, $endtimeminute, 0, $endMonth, $endDay, $endYear );     
            }
			
			if ( $starttime ) 
			{
				$dtstart = format_timestamp ( $dtstart );
				$dtend = format_timestamp ( $dtend );
			} 
			else
			{
				$dtstart = format_datestamp ( $dtstart );
				$dtend = $dtend ? format_datestamp ( $dtend ) : null;
			}


            // Store in array
            $event = array (
                  'UID'		=> md5( get_the_id() ) . "@bisonsrfc.co.uk",
                  'DTSTART'	=> $dtstart,
                  'CREATED'   => get_the_time ( 'Ymd\THis' ),
                  'SUMMARY'   => get_the_title(),
                  'DESCRIPTION' => str_replace("\n", '\n', wp_strip_all_tags ( get_the_content() ) ),
                  'URL'	=> get_the_permalink()
            );
			
			// Add in the location if there is one
			if ( $location = get_post_meta ( get_the_id(), 'address', true ) ) $event['LOCATION'] = str_replace("\n", '\n', $location);


            if ( $dtend ) $event['DTEND'] = $dtend;

              // If modified date is different to post date, add to array
              if ( get_the_modified_date() != get_the_time() ) $event['LAST-MODIFIED'] = get_the_modified_date('Ymd\THis');

            // Add array to master array
            array_push ( $ical_events, $event );

      }

      // Start output
      $output .= "BEGIN:VCALENDAR\r\n"
                .  "METHOD:PUBLISH\r\n"
                .  "VERSION:2.0\r\n"
                .  "PRODID:-//Bisons RFC.co.uk//Events Calendar//EN\r\n";

      // Loop through each event
      foreach ( $ical_events as $event )
      {
            // Start event
            $output .= "BEGIN:VEVENT\r\n";

            // Loop through each key
            foreach ( $event as $key => $value ) 
			{
					if ( is_string ( $key ) ) $output .= "$key:$value\r\n";
					else $output = "$value\r\n";
			}

            // Close event
            $output .= "END:VEVENT\r\n";	
      }

      // Close calendar
      $output .= "END:VCALENDAR\r\n";
	  	
	  // Set the correct headers
	  header('Content-type: text/calendar; charset=utf-8');
	  header('Content-Disposition: inline; filename=events-all.ics'); 
	  
	  echo $output; 
}

function add_ical_feed()
{
    add_feed('ical-all', 'ical_events_feed');
}

add_action ('init', 'add_ical_feed');