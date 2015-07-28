<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Quotation_Table extends WP_List_Table
{
	

    
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'ID'          		=> 'ID',
            'user_ID'       	=> 'utilisateur',
            'booking_ID' 		=> 'Event name',
            'booking_date' 		=> 'Date',
            'booking_object'	=> 'Devis'
        );

        return $columns;
    }
    
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    
    
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('booking_date' => array('booking_date', false));
    }
    
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data($validation = 0)
    {
	    global $wpdb;
			//LEFT JOIN $wpdb->users b ON a.user_ID = b.ID	
			$sql = $wpdb->prepare(" 
						SELECT *
						FROM ".$wpdb->prefix."online_booking a	
						WHERE a.validation = %d
						",$validation); 
					
			$results = $wpdb->get_results($sql);
			$data = json_decode(json_encode($results), true);
			//var_dump($data);
			return $data;
			
			
    }
    
    
		    // Used to display the value of the id column
		public function column_id($item)
		{
		    //return $item['ID'];
		    $del_input = '<input type="radio" name="validate" value="'.$item['ID'].'" /> ';
            return $del_input;
		}
		
		public function column_booking_object($item){
			$json_obj = $item['booking_object'];
			$tripID =  $item['ID'];
			$budget = json_decode($json_obj, true);
			$budgetMaxTotal = $budget['participants'] * $budget['budgetPerMax'];

					//var_dump($budget);
					echo '<div class="mfp-hide hidden" id="tu-'.$tripID.'">';
					echo '<div class="trip-budget-user">';
					echo '<h3>Le budget de votre event</h3>';
					echo '<div class="excerpt-user pure-g">';
					echo '<div class="pure-u-1-3">'.$budget['days'].' jours</div>';
					echo '<div class="pure-u-1-3">'.$budget['participants'].' participants </div>';
					echo '<div class="pure-u-1-3">Buget Max Total : '.$budgetMaxTotal.' </div>';
					echo '</div>';
					echo '<div class="excerpt-budget">';
					echo 'Budget Minimum par personne : '.$budget['budgetPerMin'].'<br />';
					echo 'Budget Minimum : '.$budget['budgetPerMin'] * $budget['participants'].'<br />';
					echo 'Budget Maximum par personne : '.$budget['budgetPerMax'].'<br />';
					echo 'Budget Maximum : '.$budget['budgetPerMax'] * $budget['participants'].'<br />';
					echo 'Budget global par personne : '.$budget['currentBudget'].'<br />';
					echo '</div>';
					echo 'Budget Total : '.$budget['currentBudget'] * $budget['participants'].'<br />';
					echo '<h4>Détails de votre event : </h4>';
					$trips = $budget['tripObject'];
					$budgetSingle = array();
					//var_dump(is_array($trips));
					echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">Activité</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">prix/pers</div>';
					            echo '<div class="pure-u-1-3">prix total</div>';
					echo '</div>';
					foreach ($trips as $trip) {
					    //  Check type
					    if (is_array($trip)){
					        //  Scan through inner loop
					        
					        foreach ($trip as $value) {
						        //calculate 
						        array_push($budgetSingle, $value['price']);
						        //html
						        echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">'.$value['name'].'</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">'.$value['price'].'</div>';
					            echo '<div class="pure-u-1-3">'.$value['price'] * $budget['participants'].'</div>';
					            echo '</div>';
					        }
					    }else{
					        // one, two, three
					        echo $trip;
					    }
					}
					$single_budg = array_sum($budgetSingle);
					$global_budg = $single_budg * $budget['participants'];
					echo '<div class="activity-budget-user pure-g">';
						        echo '<div class="pure-u-1-3">Budget Total</div>';
						        //echo $value['type'].'<br />';
					            echo '<div class="pure-u-1-3">'.$single_budg.'</div>';
					            echo '<div class="pure-u-1-3">'.$global_budg.'</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '<a href="#TB_inline?width=600&height=550&inlineId=tu-'.$tripID.'" class="thickbox">Devis</a>';



			//return $budgetMaxTotal;
		}
		
		/**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'ID':
            case 'user_ID':
            	$user_info = get_userdata( $item['user_ID'] );
            	//$data = $user_info->user_login.'</br >';
            	$data = $user_info->user_email.'</br >';
            	$data .= $user_info->user_firstname.' ';
            	$data .= $user_info->user_lastname.'</br >';
            	return $data;
            case 'booking_date':
            	$tripDate = $item['booking_date'];
				$newDate = date("d/m/y", strtotime($tripDate));
				return $newDate;
            case 'booking_object':
            	
            case 'booking_ID':
            case 'booking_ID':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }
    
    
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 5;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
    


}