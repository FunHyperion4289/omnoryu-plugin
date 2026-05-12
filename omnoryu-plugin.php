<?php 

/**
 * Plugin Name: Omnoryu Test Plugin
 * Description: Test
 * Author: Omnoryu
 * Version: 1.0
 * Text Domain: omnoryu-plugin
 */
defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/includes/db-installer.php';
require_once __DIR__ . '/includes/omnoryu-options.php';
require_once __DIR__ . '/includes/tracker.php';

class Omnoryu_Plugin{

    public function init() {

        $db = new Omnoryu_DB();

        $options = new Omnoryu_Options();

        $tracker = new Omnoryu_Tracker( $db );

        $options->init();

        $tracker->init();

    }

    public static function activate() {

        $db = new Omnoryu_DB();

        $db->create_table();

    }
}

register_activation_hook(__FILE__, ['Omnoryu_Plugin', 'activate']);

add_action( 'plugins_loaded', function(){

    $plugin = new Omnoryu_Plugin();

    $plugin->init();

});

