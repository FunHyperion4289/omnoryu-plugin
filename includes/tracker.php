<?php


defined( 'ABSPATH' ) || exit;

class Omnoryu_Tracker {

    private $db;
    
    private $tracked_views =[];

    public function __construct( Omnoryu_DB $db)
    {
        
        $this->db = $db;
    
    }

    public function init() {

        add_action('init', [$this, 'set_guest_id']);

        add_action('wp', function () {

            if (!is_singular()) {
                
                return;
            }

            global $post;

            if (!$post) {
                
                return;

            }
            
            $this->track_post_view($post->ID, $post->post_type);

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

    public function track_listing_view($object, $listing){
        
        if ((!$object) || (!$object->ID)) {

            return;

        }
            
        if (in_array($object->ID, $this->tracked_views)) {
        
            return;
        
        }

        $this->tracked_views[] = $object->ID;

        $this->track_post_view($object->ID, $object->post_type);
    }

    public function track_post_view($post_id, $post_type){
       
        if (!is_singular()) {
                
            return;

        }
    
        $allowed_post_types= get_option('my_selected_post_type',[]);
        
        if(!$allowed_post_types) {
            
            return;
        
            }
        
        $user_id=get_current_user_id();

        $guest_id=$user_id ? 0: $this->set_guest_id();
        
        $this->db->add_view(
            $post_id,
            $user_id,
            $guest_id
        );
    }

}