<?php

add_action( 'admin_menu', function() {
    add_options_page( 'Omnoryu Settings', 'Omnoryu Plugin', 'manage_options', 'omnoryu-plugin', 'omnoryu_render_plugin_settings_page' );
});

function omnoryu_render_plugin_settings_page() {
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

add_action( 'admin_init', function() {
    register_setting( 'omnoryu_settings_group', 'my_selected_post_type' );

    add_settings_section( 'omnoryu_section', 'Omnoryu Meta Boxes', null, 'omnoryu_plugin_page' );

    add_settings_field( 'omnoryu_post_types_field', 'Select Post Types', 'omnoryu_plugin_setting_select_post_type', 'omnoryu_plugin_page', 'omnoryu_section' );
});

function omnoryu_plugin_setting_select_post_type() {
    $selected = (array) get_option('my_selected_post_type', []);
    $types = get_post_types(['public' => true], 'objects');

    echo '<select name="my_selected_post_type[]" multiple style="min-height: 150px; width: 300px;">';
    foreach ( $types as $type ) {
        if ( $type->name === 'attachment' ) continue;
        
        $is_sel = in_array( $type->name, $selected ) ? 'selected' : '';
        printf( '<option value="%s" %s>%s</option>', esc_attr($type->name), $is_sel, esc_html($type->label) );
    }
    echo '</select>';
}
