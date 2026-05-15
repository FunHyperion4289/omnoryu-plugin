<?php

defined( 'ABSPATH' ) || exit;

class Omnoryu_DB {

    private $table_name;
    private $meta_key = 'view_count';

    public function __construct() {
        
        global $wpdb;
        
        $this->table_name = $wpdb->prefix.'omnoryu_views_table';
    
    }


    public function create_table(){
        
        global $wpdb;

        $charset = $wpdb->get_charset_collate();
        
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE IF NOT EXISTS {$this->table_name} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            timestamp BIGINT(20) NOT NULL,
            guest_id VARCHAR(64) NOT NULL,
            PRIMARY KEY (id)         
            ) $charset;";
        
        dbDelta($sql);
    
    }

    public function add_view( $post, $user_id = 0, $guest_id = ''){

        global $wpdb;

        if ( ! is_object( $post ) || empty( $post->ID ) || empty( $post->post_type ) ) {

            return;

        }

        $post_id   = $post->ID;
        $post_type = $post->post_type;

        $allowed_post_types = (array) get_option( 'my_selected_post_type', [] );
        
        if ( empty( $allowed_post_types ) || ! in_array( $post_type, $allowed_post_types, true ) ) {
            
            return;
        
        }

        $data = [
            'post_id' => $post_id,
            'timestamp' => time(),
        ];
        
        if ($user_id){
            $data['user_id'] = $user_id;
        } else{
            $data['guest_id'] = $guest_id;
        }

        $wpdb -> insert($this->table_name, $data);
        
        $count = $this->get_view_count( $post_id );

        update_post_meta( $post_id, $this->meta_key, $count+1 );
        
        return true;
    }

    public function get_view_count( $post_id ) {

        $count = get_post_meta( $post_id, $this->meta_key, true );

        return (int) $count;

    }

    public function get_user_views_count($post_id, $user_id = 0, $guest_id = ''){

        global $wpdb;

        if ($user_id) {
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(id) FROM {$this->table_name} WHERE post_id = %d AND user_id = %d",
                $post_id,
                $user_id
            ));
        } else {
            $count = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(id) FROM {$this->table_name} WHERE post_id = %d AND guest_id = %s",
                $post_id,
                $guest_id
            ));
        }

        return (int) $count;

    }
    
}