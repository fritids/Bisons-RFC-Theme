<?php

Class Form_Validator
{
    public $results;
    private $errorcount;
    private $validation_patterns;
        
        function __construct ( $validation_patterns = false)
        {
            $this->results = array();
            $this->errorcount = 0;
            
            if ( ! $validation_patterns )
            {
                $this->validation_patterns = array(
        
                    array ( 'name'      =>  'notempty',
                            'regex'     =>  '/^(?=\s*\S).*$/',
                            'message'   =>  'Field cannot be empty.'),     
        
                    array ( 'name'      =>  'needphonenum',
                            'regex'     =>  '/^[A-Za-z]{1,2}[0-9]{1,2}\s?[0-9]{1}[a-zA-Z]{2}$/',
                            'message'   =>  'Does not appear to be a valid UK postcode.' ),
                    
                    array ( 'name'      =>  'needpostcode',
                            'regex'     =>  '/^\+?[0-9\s\(\)]+$/',
                            'message'   =>  'Does not appear to be a valid phone number.'),                
                    
                    array ( 'name'      =>  'needemail',
                            'regex'     =>  '/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                            'message'   =>  'Does not appear to be a valid email address.'),                
                );
            }
        }
        
        function validate_field ( $name, $value, $type = 'notempty', $preq_field = false, $preq_value = false )
        {
            
            
            foreach ( $this->validation_patterns as $array )
            {
                if ( $array['name'] == $type ) 
                {
                    if ( preg_match ($array['regex'], $value) )
                    {
                        $this->results[$name] = array ( 'validated' => true );
                    }
                    else 
                    {
                        $this->results[$name] = array ( 'validated' => false, 'error_message' => $array['message'] );    
                        $this->errorcount++;
                    }
                }
            }
        }
        
        function validation_succeeded()
        {
            return $this->errorcount ? false : true;
        }
        
        function get_error_message( $name )
        {
            return isset ( $this->results[ $name ]['error_message'] ) ? $this->results[ $name ]['error_message'] : false;
        }
        
        function has_error ( $name )
        {
            return isset ( $this->results[ $name ]['error_message'] ) ? true : false;            
        }
}