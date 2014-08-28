<?php
function datetime_string($startdate, $enddate = false, $starttime = false, $endtime = false, $ul = true, $isodate = false) {

    $starttime = $starttime ? date("g:ia", strtotime($starttime)) : false;
    $endtime = $endtime ? date("g:ia", strtotime($endtime)) : false;

    if ($ul)
        $return = '<ul>';
  
    if ($startdate && $starttime && $endtime && (!$enddate || $enddate == $startdate))
    {
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";
        
        $return .= "<li><h4 class='timesmall'>Time</h4>$starttime until $endtime</li><li><h4 class='datesmall'>Date</h4>$startdate</li>";
        
        if ( $isodate) 
            $return .= "</time>";        
    }
        
    
    else if ($startdate && $enddate && !$endtime)
    {
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

        $return .= "<li><h4 class='datesmall'>From</h4>$startdate</li><li><h4 class='datesmall'>Until</h4>$enddate</li>";
        
        if ( $isodate) 
            $return .= "</time>";        
  

    }
    else if ($startdate && $enddate && $starttime && $endtime)
    {
        
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

        $return .= "<li><h4 class='datesmall'>From</h4>$starttime on $startdate</li><li><h4 class='datesmall'>Until</h4>$endtime on the $enddate</li>";
                   
        if ( $isodate) 
            $return .= "</time>";        
                   
    }
    
    else if ($startdate && !$enddate)
    {
        
        if ( $isodate) 
            $return .= "<time itemProp=\"startDate\" datetime=$isodate\">";

            $return .= "<li><h4 class='datesmall'>Date</h4>$startdate</li>";
            
        if ( $isodate) 
            $return .= "</time>";        

    }

    if ($ul)
        $return .= '</ul>';

    return $return;
}
