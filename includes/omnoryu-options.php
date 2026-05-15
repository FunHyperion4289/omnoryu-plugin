<?php

defined( 'ABSPATH' ) || exit;

class Omnoryu_Options {

    public function init(){

        add_action( 'admin_init', [$this, 'register_settings']);

        add_action( 'admin_menu', function() {
        
        add_options_page( 'Omnoryu Settings', 
            'Omnoryu Plugin', 
            'manage_options', 
            'omnoryu-plugin', 
            [$this, 'omnoryu_render_plugin_settings_page']
            );
        });

    }

    public function register_settings() {

        add_settings_section( 'omnoryu_section', 
            'Omnoryu Meta Boxes', 
            null, 
            'omnoryu_plugin_page' 
        );

        foreach ($this->get_settings_fields() as $id => $field ) {
            
            register_setting( 'omnoryu_settings_group', $id );
            
            add_settings_field( $id,
                $field['title'],
                [$this, 'omnoryu_plugin_setting_select_post_type'],            
                'omnoryu_plugin_page', 
                'omnoryu_section',
                ['id' => $id, 'field' => $field]
            );

        }
    
    }
    
    public function omnoryu_render_plugin_settings_page() {
        ?>
        <div class="wrap">
            <h2>Example Plugin Settings</h2>
            <form action="options.php" method="post">
                <?php 
                settings_fields( 'omnoryu_settings_group' );
                do_settings_sections( 'omnoryu_plugin_page' ); 
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    public function omnoryu_plugin_setting_select_post_type($args){

        $id = $args['id'];
        $field = $args['field'];
        $value = (array) get_option( $id, [] );

        echo '<select name="' . esc_attr($id) . '[]" multiple style="min-height: 150px; width: 300px;">';
        
        foreach ( $field['options'] as $val => $label ) {
            
            $is_sel = in_array( $val, $value ) ? 'selected' : '';

            printf( '<option value="%s" %s>%s</option>', esc_attr($val), $is_sel, esc_html($label) );
        
        } 
        
    }

    private function get_settings_fields() {
        return [
            'my_selected_post_type' => [
                'title' => 'Select Post Type',
                'options' => $this->get_post_types_options(),
            ]
        ];
    }

    private function get_post_types_options(){
        
        $types = get_post_types(['public' => true], 'objects');

        $options = [];

        foreach ( $types as $type ) {
            
            $options[$type->name] = $type->label;
        
        }
        
        return $options;

    }

}


