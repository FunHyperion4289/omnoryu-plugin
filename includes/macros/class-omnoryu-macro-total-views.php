<?php

class Omnoryu_Macro_Total_Views extends \Jet_Engine_Base_Macros { 

    private $db;

    public function __construct($db) {
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
        $post_id        = $current_object ? $current_object->ID : get_the_ID();

        if (!$post_id){
            return 0;
        }

        return $this->db->get_view_count($post_id);
	}
}