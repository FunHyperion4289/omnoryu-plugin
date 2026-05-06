<?php

class DB_Installer {
    public static function database_creation(){
        global $wpdb;
        $omnoryu_views_table = $wpdb->prefix.'omnoryu_views_table';
        $charset = $wpdb->get_charset_collate();
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $sql = "CREATE TABLE $omnoryu_views_table (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            user_id BIGINT(20) UNSIGNED NOT NULL,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            timestamp BIGINT(20) NOT NULL,
            guest_id VARCHAR(64) NOT NULL,
            view_count BIGINT(20) UNSIGNED NOT NULL,
            PRIMARY KEY (id)         
            ) $charset;";
        dbDelta($sql);
    }

    
}

