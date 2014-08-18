<?php




class Membership_Forms_Table extends WP_List_Table_Copy
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
           // Get membership form information and insert it into data array
           $membership_form = new WP_Query ( array (
                     'post_type' => 'membership_form',
                     'posts_per_page' => 1,
                     'orderby'   => 'date',
                     'order'     => 'ASC',
                     'author'   => $user->data->ID
                     ) );
                  
            while ( $membership_form->have_posts() )
            {
                $membership_form->the_post();
                
                // Create address HTML string
                $address = array (
                    get_post_meta(get_the_id(), 'streetaddyl1', true),
                    get_post_meta(get_the_id(), 'streetaddyl2', true),
                    get_post_meta(get_the_id(), 'streetaddytown', true),
                    get_post_meta(get_the_id(), 'postcode', true)
                );
                
                $address = implode ( '<br />', $address);
                
                
                if ( get_post_meta(get_the_id(), 'sameaddress' , true ) )
                {
                    $nokaddress = 'Same address';
                }
                else 
                {
                    $nokaddress = array (
                        get_post_meta(get_the_id(), 'nokstreetaddy' , true),
                        get_post_meta(get_the_id(), 'nokpostcode' , true)
                    );
                    $nokaddress = implode ( '<br />', $nokaddress );
                }
                
                // Create DOB string
                $dob = get_post_meta(get_the_id(), 'dob-day', true).'/'.get_post_meta(get_the_id(), 'dob-month', true).'/'.get_post_meta(get_the_id(), 'dob-year', true);
               
               if ( get_post_meta(get_the_id(), 'medconsdisabyesno', true) == "No" ) 
               {
                   $medCons = '<strong>None</strong>';
               }
               else
               {
                   $medCons = '<ul>';
                   for ( $ii = 1; $ii == 1 || $ii <= get_post_meta(get_the_id(), 'condsdisablities_rowcount', true); $ii++ )
                   {
                       $condition = array();
                       if ( get_post_meta(get_the_id(), 'condsdisablities_name_row' . $ii, true) )
                            $condition[] = '<strong>'.get_post_meta(get_the_id(), 'condsdisablities_name_row' . $ii, true).'</strong>';
                       
                       if ( get_post_meta(get_the_id(), 'condsdisablities_drugname_row' . $ii, true) )
                            $condition[] = '<em>Medication:</em> '.get_post_meta(get_the_id(), 'condsdisablities_drugname_row' . $ii, true);

                       if ( get_post_meta(get_the_id(), 'condsdisablities_drugname_row' . $ii, true) )
                            $condition[] = '<em>Dose/Frequency:</em> '.get_post_meta(get_the_id(), 'condsdisablities_drugdose_freq_row' . $ii, true);
                        
                        if ( sizeof ( $conditions) )
                            $medCons .= '<li>'.implode ( '<br />', $condition ).'</li>';
                   }
                   $medCons .= '</ul>';
               }
               
               if ( get_post_meta(get_the_id(), 'allergiesyesno', true) == "No" ) 
               {
                   $allergies = '<strong>None</strong>';
               }
               else
               {
                   $allergies = '<ul>';
                   for ( $ii = 1; $ii == 1 || $ii <= get_post_meta(get_the_id(), 'allergies_rowcount', true); $ii++ )
                   {
         
                        
                       $condition = array();
                       if ( get_post_meta(get_the_id(), 'allergies_name_row' . $ii, true) )
                            $condition[] = '<strong>'.get_post_meta(get_the_id(), 'allergies_name_row' . $ii, true).'</strong>';
                       
                       if ( get_post_meta(get_the_id(), 'allergies_drugname_row' . $ii, true) )
                            $condition[] = '<em>Medication:</em> '.get_post_meta(get_the_id(), 'allergies_drugname_row' . $ii, true);

                       if ( get_post_meta(get_the_id(), 'allergies_drugdose_freq_row' . $ii, true) )
                            $condition[] = '<em>Dose/Frequency:</em> '.get_post_meta(get_the_id(), 'allergies_drugdose_freq_row' . $ii, true);
                        
                        if ( sizeof ( $condition ) )
                            $allergies .= '<li>'.implode ( '<br />', $condition ).'</li>';
                   }
                   $allergies .= '</ul>';
               }
               
               if ( get_post_meta(get_the_id(), 'injuredyesno', true) == "No" ) 
               {
                   $injuries = '<strong>None</strong>';
               }
               else
               {
                   $injuries = '<ul>';
                   for ( $ii = 1; $ii == 1 || $ii <= get_post_meta(get_the_id(), 'injuries_rowcount', true); $ii++ )
                   {
                        $condition = array(
                            '<strong>'.get_post_meta(get_the_id(), 'injuries_name_row' . $ii, true).'</strong>',
                            '<em>When:</em>'.get_post_meta(get_the_id(), 'injuries_when_row' . $ii, true),
                            '<em>Treatment received:</em>'.get_post_meta(get_the_id(), 'injuries_treatmentreceived_row' . $ii, true),
                            '<em>Who treated:</em>'.get_post_meta(get_the_id(), 'injuries_who_row' . $ii, true),
                            '<em>Status:</em>'.get_post_meta(get_the_id(), 'injuries_status_row' . $ii, true)
                        );
                       $condition = array();
                       if ( get_post_meta(get_the_id(), 'injuries_name_row' . $ii, true) )
                            $condition[] = '<strong>'.get_post_meta(get_the_id(), 'injuries_name_row' . $ii, true).'</strong>';
                       
                       if ( get_post_meta(get_the_id(), 'injuries_when_row' . $ii, true) )
                            $condition[] = '<em>When:</em> '.get_post_meta(get_the_id(), 'injuries_when_row' . $ii, true);

                       if ( get_post_meta(get_the_id(), 'injuries_treatmentreceived_row' . $ii, true) )
                            $condition[] = '<em>Treatment received:</em> '.get_post_meta(get_the_id(), 'injuries_treatmentreceived_row' . $ii, true);

                       if ( get_post_meta(get_the_id(), 'injuries_who_row' . $ii, true) )
                            $condition[] = '<em>Who treated:</em> '.get_post_meta(get_the_id(), 'injuries_who_row' . $ii, true);
                       
                       if ( get_post_meta(get_the_id(), 'injuries_status_row' . $ii, true) )
                            $condition[] = '<em>Status:</em> '.get_post_meta(get_the_id(), 'injuries_status_row' . $ii, true);

                       
                        if ( sizeof ( $condition ) )
                            $injuries .= '<li>'.implode ( '<br />', $condition ).'</li>';
                   }
                   $injuries .= '</ul>';
               }
               
                $conditions = array(); 
                if ( get_post_meta(get_the_id(), 'fainting' , true) == 'on' ) $conditions[] = 'Fainting';
                if ( get_post_meta(get_the_id(), 'dizzyturns' , true) == 'on' ) $conditions[] = 'Dizzy turns';
                if ( get_post_meta(get_the_id(), 'breathlessness' , true) == 'on' ) $conditions[] = 'Breathlessness or more easily tired than team-mates';
                if ( get_post_meta(get_the_id(), 'bloodpressure' , true) == 'on' ) $conditions[] = 'History of high blood pressure';
                if ( get_post_meta(get_the_id(), 'diabetes' , true) == 'on' ) $conditions[] = 'Diabetes';
                if ( get_post_meta(get_the_id(), 'palpitations' , true) == 'on' ) $conditions[] = 'Palpitations';
                if ( get_post_meta(get_the_id(), 'chestpain' , true) == 'on' ) $conditions[] = 'Chest pain or tightness';
                if ( get_post_meta(get_the_id(), 'suddendeath' , true) == 'on' ) $conditions[] = 'Sudden death in immediate family of anyone under fifty';
                if ( get_post_meta(get_the_id(), 'smoking' , true) == 'on' ) $conditions[] = 'Smoker';
                $conditionsstring = "";
                for ( $ii = 0; $conditions[$ii]; $ii++ ) $conditionsstring .= ( $ii ? ', ' : null ).$conditions[$ii];
                $conditionsstring = $conditionsstring ? $conditionsstring : "None"; 

                $playedbefore = ( get_post_meta(get_the_id(), 'playedbefore' , true) == 'Yes' ) ? 'Yes - '.get_post_meta(get_the_id(), 'whereandseasons' , true) : 'No'; 
                $data[] = array(
                    'id'                 => get_the_id(),
                    'type'                    => get_post_meta(get_the_id(), 'joiningas' , true),
                    'fullname'                => get_post_meta(get_the_id(), 'firstname', true).' '.get_post_meta(get_the_id(), 'surname', true),
                    'dob'                     => $dob,
                    'age'                     => getage($dob),
                    'gender'                  => get_post_meta(get_the_id(), 'gender', true) == "Other" ? get_post_meta(get_the_id(), 'othergender', true) : get_post_meta(get_the_id(), 'gender', true),
                    'email'                   => get_post_meta(get_the_id(), 'email_addy', true),
                    'telephone'               => get_post_meta(get_the_id(), 'contact_number', true),
                    'address'                 => $address, 
                    'nokname'                 => get_post_meta(get_the_id(), 'nokfirstname' , true).' '.get_post_meta(get_the_id(), 'noksurname', true),
                    'nokrelationship'         => get_post_meta(get_the_id(), 'nokrelationship' , true),
                    'nokphone'                => get_post_meta(get_the_id(), 'nokcontactnumber', true),
                    'nokaddress'              => $nokaddress,
                    'medicalConditions'       => $medCons,
                    'allergies'               => $allergies,
                    'injuries'                => $injuries,
                    'otherSportsOrActivities' => get_post_meta(get_the_id(), 'othersports' , true),
                    'traininghours'           => get_post_meta(get_the_id(), 'hoursaweektrain' , true),
                    'playedbefore'            => $playedbefore,
                    'height'                  => get_post_meta(get_the_id(), 'height' , true),
                    'weight'                  => get_post_meta(get_the_id(), 'weight' , true),
                    'cardiacQues'             => $conditionsstring,
                    'howDidYouHear'           => get_post_meta(get_the_id(), 'howdidyouhear' , true),
                    'whatCanYouBring'         => get_post_meta(get_the_id(), 'whatcanyoubring' , true)
                );
            }
        }
        $this->data = $data;
        
        $hook = add_menu_page('My Plugin List Table', 'My List Table Example', 'activate_plugins', 'my_list_test', 'my_render_list_page');
        add_action( "load-$hook", 'add_options' );
        
        parent::__construct();
      }
      
    function get_columns()
    {
            $columns = array(
                    'cb'                      => '<input type="checkbox" />',
                    'type'                    => 'Type',
                    'fullname'                => 'Name',
                    'dob'                     => 'DOB',
                    'age'                     => 'Age',
                    'gender'                  => 'Gender',
                    'email'                   => 'Email',
                    'telephone'               => 'Phone',
                    'address'                 => 'Address', 
                    'nokname'                 => 'NOK name',
                    'nokphone'                => 'NOK phone',
                    'nokrelationship'         => 'NOK relationship',
                    'nokaddress'              => 'NOK address',
                    'medicalConditions'       => 'Medical conditions/disabilities',
                    'allergies'               => 'Allergies',
                    'injuries'                => 'Injuries',
                    'otherSportsOrActivities' => 'Other sports or activities',
                    'traininghours'           => 'Training hours a week',
                    'playedbefore'            => 'Played before?',
                    'height'                  => 'Height',
                    'weight'                  => 'Weight',
                    'cardiacQues'             => 'Cardiac questionnaire',
                    'howDidYouHear'           => 'How did you hear about us',
                    'whatCanYouBring'         => 'Skills'
            );
            
            return $columns; 
      }
      
      function usort_reorder( $a, $b )
      {
        // If no sort, default to date
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'fullname';
        
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
                    'type'                    => array('type', false),
                    'fullname'                => array('fullname', false),
                    'age'                     => array('age', false),
                    'gender'                  => array('gender', false),
                    'email'                   => array('email', false),
                    'nokname'                 => array('nokname', false),
                    'nokrelationship'         => array('nokrelationship', false),
                    'traininghours'           => array('traininghours', false),
                    'playedbefore'            => array('playedbefore', false),
                    'height'                  => array('height', false),
                    'weight'                  => array('weight', false),
                    'cardiacQues'             => array('cardiacQues', false),
                  );
            return $columns;
      }
      function prepare_items()
      {
            $this->_column_headers = $this->get_column_info();
            usort( $this->data, array( &$this, 'usort_reorder' ) );
            $total_items = count($this->data);
            $per_page = $this->get_items_per_page('forms_per_page', 5);
            $current_page = $this->get_pagenum();
            $this->found_data = array_slice($this->data,(($current_page-1)*$per_page),$per_page);
            $this->set_pagination_args( array(
                'total_items' => $total_items,                  //WE have to calculate the total number of items
                'per_page'    => $per_page                     //WE have to determine how many items to show on a page
            ) );
            $this->items = $this->found_data;  
            

            
      }
      
    function column_default( $item, $column_name )
      {
            
            switch ( $column_name )
            {
                    case 'type':
                    case 'fullname':
                    case 'dob':
                    case 'age':
                    case 'gender':
                    case 'email':
                    case 'telephone':
                    case 'nokphone':
                    case 'address': 
                    case 'nokname':
                    case 'nokrelationship':
                    case 'nokaddress':
                    case 'medicalConditions':
                    case 'allergies':
                    case 'injuries':
                    case 'otherSportsOrActivities':
                    case 'traininghours':
                    case 'playedbefore':
                    case 'height':
                    case 'weight':
                    case 'cardiacQues':
                    case 'howDidYouHear':
                    case 'whatCanYouBring':
                    return $item [ $column_name ];
                  default:
                        new dBug ( $item );
            }
      }

    function get_bulk_actions()
    {
        $actions = array(
            'bulk_email'    => 'Send Email',
            'reset_pass'    => 'Reset Passwords'
        );
        
        return $actions;
    }
    function column_fullname ( $item )
    {
        
        $actions = array(
            'resetPass' => sprintf('<a href="?page=%s&action=%s&user=%s">Reset Password</a>',$_REQUEST['page'],'pass_reset',$item['id']),
            'email' => sprintf('<a href="?page=%s&action=%s&user=%s">Email</a>',$_REQUEST['page'],'single_email',$item['id'])
        );
        
        return sprintf('%1$s %2$s', $item['fullname'], $this->row_actions($actions) );
    }
    
    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />', $item['id']
        );    
    }
}

