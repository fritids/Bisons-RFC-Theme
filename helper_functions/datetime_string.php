<?php
function datetime_string ( $startdate, $enddate = false, $starttime = false, $endtime = false )
{
            
	$starttime = $starttime ? date("g:ia", strtotime( $starttime ) ) : false;
	$endtime = $endtime ? date("g:ia", strtotime( $endtime ) ) : false;
      
	if ( $startdate && $starttime && $endtime && ( ! $enddate || $enddate == $startdate ) )
            $return = "$starttime until $endtime on the $startdate";
          
      else if ( $startdate && $starttime && ! $endtime && ( ! $enddate || $enddate == $startdate ) )
            $return = "$starttime on the $startdate";

      else if ( $startdate && $enddate && $starttime && ! $endtime )
            $return = "$starttime on the $startdate until the $enddate";

      else if ( $startdate && $enddate && ! $endtime )
            $return = "$startdate until the $enddate";
          
      else if ( $startdate && $enddate && $starttime && $endtime )
            $return = "$starttime on the $startdate until $endtime on the $enddate";
          
      else if ( $startdate && ! $enddate )
            $return = "$startdate";
      
      return $return;
}