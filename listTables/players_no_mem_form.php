<?php

class Players_No_Mem_form extends WP_List_Table_Copy
{
      private $users;
      
      function __construct()
      {
        // Get fixtures from Wordpress database
        $users = get_users();
        
        // Create table data array
        $data = array();
        foreach ( $users as $user )
        {
            $data[] = array(
                'name'              => $user->data->display_name,
                'dateReg'   => $user->data->user_registered,
                'type'              => $user->roles[0],
                'email'             => $user->data->user_email
            );
        }
        $this->data = $data;
        
        
        parent::__construct();
      }
      
    function get_columns()
    {
            $columns = array(
                  'name' => 'Name',
                  'dateReg' => 'Date Registered',
                  'type' => 'Type',
                  'email' => 'Email',
            );
            
            return $columns; 
      }
      
      function usort_reorder( $a, $b )
      {
        // If no sort, default to date
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'dateReg';
        
            // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        
            // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        
            // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }
      
      function get_sortable_columns()
      {
            $columns = array(
                  'name' => array('name', false),
                  'dateReg' => array('dateReg', false),
                  'type' =>  array('type', false),
                  'email' => array('email', false),
                  );
            return $columns;
      }
      function prepare_items()
      {
            $columns = $this->get_columns();
            $hidden = array();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, $hidden, $sortable);
            usort( $this->data, array( &$this, 'usort_reorder' ) );
            $this->items = $this->data;  
            
      }
      
    function column_default( $item, $column_name )
      {
            
            switch ( $column_name )
            {
                  case 'name':
                  case 'dateReg':
                  case 'type':
                  case 'email':
                    return $item [ $column_name ];
                  default:
                        new dBug ( $item );
            }
      }
}

