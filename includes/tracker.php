<?php

function set_guest_id() {

    if (!isset($_COOKIE['my_custom_cookie'])) {
        $value = wp_generate_uuid4();
        $expiry = time() + (86400 * 30);
        
        setcookie('my_custom_cookie', $value, $expiry, COOKIEPATH, COOKIE_DOMAIN);
        $_COOKIE['my_custom_cookie'] = $value;
        return $value;
    }
    return $_COOKIE['my_custom_cookie'];

}
add_action('init', 'set_guest_id');
add_action('wp', function () {
    if (!is_singular()) return;

    global $post;
    if (!$post) return;

    track_post_view($post->ID, $post->post_type);
});

add_action('jet-engine/listings/data/set-current-object','track_listing_view',10,2);

function track_listing_view($object, $listing){
    if ((!$object) || (!$object->ID)) return;
    static $tracked_views = [];
    if (in_array($object->ID, $tracked_views)) return;
    $tracked_views[] = $object->ID;
    track_post_view($object->ID, $object->post_type);
}

function track_post_view($post_id, $post_type){
    if (!is_singular()) return;
    global $post,$wpdb;

    $allowed_post_types= get_option('my_selected_post_type',[]);
    if(!$allowed_post_types) return;
    $user_id=get_current_user_id();

    $guest_id=$user_id ? 0: set_guest_id();
    $table=$wpdb->prefix.'omnoryu_views_table';
    
    if ($user_id) {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE post_id = %d AND user_id = %d",
            $post_id,
            $user_id
        ));
    } else {
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table WHERE post_id = %d AND guest_id = %s",
            $post_id,
            $guest_id
        ));
    }

    if ($exists) return;
    $data = [
        'post_id' => $post_id,
        'timestamp' => current_time('mysql'),
    ];
    
    if ($user_id){
        $data['user_id'] = $user_id;
    } else{
        $data['guest_id'] = $guest_id;
    };
    $result = $wpdb -> insert($table, $data);
    $count = (int) get_post_meta($post_id, 'view_count', true);
    update_post_meta($post_id, 'view_count', $count+1);
    
}