<?php
/*
Plugin Name: Theme Options
Description: A plugin to add theme options in the admin panel.
Version: 1.1
Author: kapitanweb.pl
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Theme_Options_Plugin {
    private $options;

    public function __construct() {
        add_action('admin_menu', array($this, 'add_plugin_page'));
        add_action('admin_init', array($this, 'page_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_color_picker'));
    }

    public function add_plugin_page() {
        add_menu_page(
            'Theme Options', 
            'Theme Options', 
            'manage_options', 
            'theme-options', 
            array($this, 'create_admin_page'), 
            'dashicons-admin-generic', 
            60
        );
    }

    public function create_admin_page() {
        $this->options = get_option('theme_options');
        ?>
        <div class="wrap">
            <h1>Theme Options</h1>
            <h2 class="nav-tab-wrapper">
                <a href="#header" class="nav-tab nav-tab-active">Header</a>
                <a href="#footer" class="nav-tab">Footer</a>
            </h2>
            <form method="post" action="options.php">
                <?php
                settings_fields('theme_options_group');
                ?>
                <div id="header" class="tab-content">
                    <?php do_settings_sections('theme-options-header'); ?>
                </div>
                <div id="footer" class="tab-content" style="display:none;">
                    <?php do_settings_sections('theme-options-footer'); ?>
                </div>
                <?php
                submit_button();
                ?>
            </form>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const tabs = document.querySelectorAll('.nav-tab');
                const contents = document.querySelectorAll('.tab-content');

                tabs.forEach(tab => {
                    tab.addEventListener('click', function(event) {
                        event.preventDefault();
                        tabs.forEach(t => t.classList.remove('nav-tab-active'));
                        contents.forEach(c => c.style.display = 'none');

                        tab.classList.add('nav-tab-active');
                        document.querySelector(tab.getAttribute('href')).style.display = 'block';
                    });
                });

                const uploadButton = document.getElementById('upload_image_button');
                const imagePreview = document.getElementById('image_preview');
                const imageInput = document.getElementById('header_image');

                uploadButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    var image = wp.media({
                        title: 'Upload Image',
                        multiple: false
                    }).open()
                    .on('select', function(e) {
                        var uploaded_image = image.state().get('selection').first();
                        var image_url = uploaded_image.toJSON().url;
                        imageInput.value = image_url;
                        imagePreview.src = image_url;
                        imagePreview.style.display = 'block';
                    });
                });
            });
        </script>
        <?php
    }

    public function page_init() {
        register_setting(
            'theme_options_group', 
            'theme_options', 
            array($this, 'sanitize')
        );

        add_settings_section(
            'header_section', 
            'Header', 
            null, 
            'theme-options-header'
        );

        add_settings_field(
            'header_text', 
            'Header Text', 
            array($this, 'header_text_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_textarea', 
            'Header Textarea', 
            array($this, 'header_textarea_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_radio', 
            'Header Radio', 
            array($this, 'header_radio_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_editor', 
            'Header Editor', 
            array($this, 'header_editor_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_image', 
            'Header Image', 
            array($this, 'header_image_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_number', 
            'Header Number', 
            array($this, 'header_number_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_checkbox', 
            'Header Checkbox', 
            array($this, 'header_checkbox_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_field(
            'header_colorpicker', 
            'Header Color Picker', 
            array($this, 'header_colorpicker_callback'), 
            'theme-options-header', 
            'header_section'
        );
		
        add_settings_field(
            'header_colorpicker2', 
            'Header Color Picker 2', 
            array($this, 'header_colorpicker2_callback'), 
            'theme-options-header', 
            'header_section'
        );

        add_settings_section(
            'footer_section', 
            'Footer', 
            null, 
            'theme-options-footer'
        );

        add_settings_field(
            'footer_select', 
            'Footer Select', 
            array($this, 'footer_select_callback'), 
            'theme-options-footer', 
            'footer_section'
        );
    }

    public function enqueue_color_picker($hook_suffix) {
        if ($hook_suffix === 'toplevel_page_theme-options') {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('theme-options-script', plugins_url('theme-options-script.js', __FILE__), array('wp-color-picker'), false, true);
        }
    }

    public function sanitize($input) {
        $new_input = array();
        if (isset($input['header_text'])) {
            $new_input['header_text'] = sanitize_text_field($input['header_text']);
        }
        if (isset($input['header_textarea'])) {
            $new_input['header_textarea'] = sanitize_textarea_field($input['header_textarea']);
        }
        if (isset($input['header_radio'])) {
            $new_input['header_radio'] = sanitize_text_field($input['header_radio']);
        }
        if (isset($input['header_editor'])) {
            $new_input['header_editor'] = wp_kses_post($input['header_editor']);
        }
        if (isset($input['header_image'])) {
            $new_input['header_image'] = esc_url_raw($input['header_image']);
        }
        if (isset($input['header_number'])) {
            $new_input['header_number'] = intval($input['header_number']);
        }
        if (isset($input['header_checkbox'])) {
            $new_input['header_checkbox'] = absint($input['header_checkbox']);
        }
        if (isset($input['header_colorpicker'])) {
            $new_input['header_colorpicker'] = sanitize_hex_color($input['header_colorpicker']);
        }
        if (isset($input['header_colorpicker2'])) {
            $new_input['header_colorpicker2'] = sanitize_hex_color($input['header_colorpicker2']);
        }
        if (isset($input['footer_select'])) {
            $new_input['footer_select'] = sanitize_text_field($input['footer_select']);
        }
        return $new_input;
    }

    public function header_text_callback() {
        printf(
            '<input type="text" id="header_text" name="theme_options[header_text]" value="%s" />',
            isset($this->options['header_text']) ? esc_attr($this->options['header_text']) : ''
        );
    }

    public function header_textarea_callback() {
        printf(
            '<textarea id="header_textarea" name="theme_options[header_textarea]">%s</textarea>',
            isset($this->options['header_textarea']) ? esc_textarea($this->options['header_textarea']) : ''
        );
    }

    public function header_radio_callback() {
        $options = array('Option 1', 'Option 2', 'Option 3');
        foreach ($options as $option) {
            $checked = (isset($this->options['header_radio']) && $this->options['header_radio'] === $option) ? 'checked' : '';
            printf(
                '<label><input type="radio" name="theme_options[header_radio]" value="%s" %s /> %s</label><br>',
                esc_attr($option), $checked, esc_html($option)
            );
        }
    }

    public function header_editor_callback() {
        $content = isset($this->options['header_editor']) ? $this->options['header_editor'] : '';
        wp_editor($content, 'header_editor', array(
            'textarea_name' => 'theme_options[header_editor]',
        ));
    }

    public function header_image_callback() {
        $image = isset($this->options['header_image']) ? esc_url($this->options['header_image']) : '';
        printf(
            '<input type="text" id="header_image" name="theme_options[header_image]" value="%s" />',
            $image
        );
        echo '<input type="button" class="button" value="Upload Image" id="upload_image_button" />';
        echo '<br><img id="image_preview" src="' . $image . '" style="max-width: 200px; display: ' . ($image ? 'block' : 'none') . ';" />';
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('#upload_image_button').click(function(e) {
                    e.preventDefault();
                    var image = wp.media({
                        title: 'Upload Image',
                        multiple: false
                    }).open()
                    .on('select', function(e) {
                        var uploaded_image = image.state().get('selection').first();
                        var image_url = uploaded_image.toJSON().url;
                        $('#header_image').val(image_url);
                        $('#image_preview').attr('src', image_url).show();
                    });
                });
            });
        </script>
        <?php
    }

    public function header_number_callback() {
        printf(
            '<input type="number" id="header_number" name="theme_options[header_number]" value="%s" />',
            isset($this->options['header_number']) ? esc_attr($this->options['header_number']) : ''
        );
    }

    public function header_checkbox_callback() {
        $checked = isset($this->options['header_checkbox']) ? 'checked' : '';
        printf(
            '<input type="checkbox" id="header_checkbox" name="theme_options[header_checkbox]" value="1" %s />',
            $checked
        );
    }

    public function header_colorpicker_callback() {
        printf(
            '<input type="text" id="header_colorpicker" name="theme_options[header_colorpicker]" value="%s" class="my-color-field" data-default-color="#effeff" />',
            isset($this->options['header_colorpicker']) ? esc_attr($this->options['header_colorpicker']) : ''
        );
    }
	
    public function header_colorpicker2_callback() {
        printf(
            '<input type="text" id="header_colorpicker2" name="theme_options[header_colorpicker2]" value="%s" class="my-color-field" data-default-color="#effeff" />',
            isset($this->options['header_colorpicker2']) ? esc_attr($this->options['header_colorpicker2']) : ''
        );
    }

    public function footer_select_callback() {
        $options = array('Option 1', 'Option 2', 'Option 3');
        echo '<select id="footer_select" name="theme_options[footer_select]">';
        foreach ($options as $option) {
            $selected = (isset($this->options['footer_select']) && $this->options['footer_select'] === $option) ? 'selected' : '';
            printf('<option value="%s" %s>%s</option>', esc_attr($option), $selected, esc_html($option));
        }
        echo '</select>';
    }
}

if (is_admin()) {
    $theme_options_plugin = new Theme_Options_Plugin();
}
?>
