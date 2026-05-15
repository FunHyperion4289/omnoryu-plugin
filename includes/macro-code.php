<?php

class Omnoryu_Macro_Total_Views extends \Jet_Engine_Base_Macros{ 

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
        parent::__construct(); 
    }

	public function macros_tag() {
		return 'omnoryu_total_views';
	}

	public function macros_name() {
		return 'Omnoryu Total Views';
	}

	public function macros_args() {
		return [];
	}

	public function macros_callback( $args = array() ) {
	
        $current_object = jet_engine()->listings->data->get_current_object();

        $post_id = $current_object ? $current_object->ID : get_the_ID();

        if (!$post_id){
            return 0;
        }

        $total_views = $this->db->get_view_count($post_id);

        return $total_views;
	}
    
}

class Omnoryu_Macro_User_Views extends \Jet_Engine_Base_Macros{ 

    private $db;

    public function __construct( $db ) {
        $this->db = $db;
        parent::__construct(); 
    }

	public function macros_tag() {
		return 'omnoryu_user_views';
	}

	public function macros_name() {
		return 'Omnoryu Current User Views';
	}

	public function macros_args() {
		return [];
	}

	public function macros_callback( $args = array() ) {
        global $wpdb;
        $current_object = jet_engine()->listings->data->get_current_object();

        $post_id = $current_object ? $current_object->ID : get_the_ID();

        if (!$post_id){
            return 0;
        }

        $charset = $wpdb->get_charset_collate();

        $user_id=get_current_user_id();
        $guest_id = '';
        if ( ! $user_id && isset( $_COOKIE['my_custom_cookie'] ) ) {
            $guest_id = $_COOKIE['my_custom_cookie'];
        }

        $total_views = $this->db->get_user_views_count($post_id, $user_id, $guest_id);
        
        return $total_views;
	}

}