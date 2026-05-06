<?php 

/**
 * Plugin Name: Omnoryu Test Plugin
 * Description: Test
 * Author: Omnoryu
 * Version: 1.0
 * Text Domain: omnoryu-plugin
 */
defined('ABSPATH') || exit;

require_once __DIR__ . '/includes/db-installer.php';
require_once __DIR__ . '/includes/omnoryu-options.php';
require_once __DIR__ . '/includes/tracker.php';

register_activation_hook(__FILE__, ['DB_Installer', 'database_creation']);

