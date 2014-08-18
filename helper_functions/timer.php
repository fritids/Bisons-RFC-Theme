<?php
/**
 * Helper class to time the execution of Wordpress
 */
class ScriptTimer {
    private $start;
    private $counts;
    
    function __construct() {
        $this->start = microtime(true);
        $this->counts = array();
    }

    function record_execution_time( $label = false ) {
        $this->counts[ sizeof( $this->counts ) ] = array ( 'label' => $label, 'time' => microtime(true) - $this->start );
    }
    
    function print_execution_times() {
        if ($_GET['debug'] == 'true') {
            echo "<!--\n\nPageload timings (seconds)\n--------------------------\n";
            foreach ( $this->counts as $index => $time )
            {
                $label = $time['label'] ? ' ('.$time['label'].')' : false;
                $time = round($time['time'], 4);
                echo "$index$label: $time\n";
            } 
            echo "--------------------------\n\n-->\n";
        }
    }
}
