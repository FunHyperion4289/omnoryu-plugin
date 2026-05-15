<?php


defined( 'ABSPATH' ) || exit;

class Omnoryu_Tracker {

    private $db;
    
    private $tracked_views =[];

    public function __construct( Omnoryu_DB $db)
    {
        
        $this->db = $db;
    
    }

    private function is_editor_mode() {

        if ( is_admin() ) {
            return true;
        }

        if ( isset( $_GET['elementor-preview'] ) ) {
            return true;
        }

        if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
            return true;
        }

        return false;

    }

    public function init() {

        add_action('wp', function () {

            if (!is_singular()) {
                
                return;
            }

            global $post;

            if (!$post) {
                
                return;

            }
            
            $this->track_post_view($post);

        });

        add_action('jet-engine/listings/data/set-current-object', [$this, 'track_listing_view'],10,2);
    
        }

    public function set_guest_id() {

        if (!isset($_COOKIE['my_custom_cookie'])) {

            $value = wp_generate_uuid4();

            $expiry = time() + (86400 * 30);
            
            setcookie('my_custom_cookie', $value, $expiry, COOKIEPATH, COOKIE_DOMAIN);
            
            $_COOKIE['my_custom_cookie'] = $value;

            return $value;

        }
        
        return $_COOKIE['my_custom_cookie'];

    }

    public function track_listing_view($object){
        
        if ((!$object) || (!$object->ID)) {

            return;

        }
            
        if (in_array($object->ID, $this->tracked_views)) {
        
            return;
        
        }

        $this->tracked_views[] = $object->ID;

        $this->track_post_view($object);
    }

    public function track_post_view($post){
        
        if ( $this->is_editor_mode() ) {
            
            return;
       
        }

        $user_id=get_current_user_id();

        $guest_id=$user_id ? 0: $this->set_guest_id();
        
        $this->db->add_view(
            $post,
            $user_id,
            $guest_id
        );
    }

}