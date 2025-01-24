<?php
/*
Plugin Name: Custom Widget Classes
Description: Allows adding custom CSS classes to widgets.
Version: 1.0
Author: kapitanweb.pl
*/

function cwc_add_custom_class_field($widget, $return, $instance) {
    if (!isset($instance['custom_class'])) {
        $instance['custom_class'] = '';
    }
    ?>
    <p>
        <label for="<?php echo $widget->get_field_id('custom_class'); ?>"><?php _e('Custom Class:'); ?></label>
        <input class="widefat" id="<?php echo $widget->get_field_id('custom_class'); ?>" name="<?php echo $widget->get_field_name('custom_class'); ?>" type="text" value="<?php echo esc_attr($instance['custom_class']); ?>" />
    </p>
    <?php
}

function cwc_save_custom_class_field($instance, $new_instance, $old_instance) {
    $instance['custom_class'] = strip_tags($new_instance['custom_class']);
    return $instance;
}

function cwc_add_custom_class_to_widget($params) {
    global $wp_registered_widgets;
    $widget_id = $params[0]['widget_id'];
    $widget_obj = $wp_registered_widgets[$widget_id];
    $widget_opt = get_option($widget_obj['callback'][0]->option_name);
    $widget_num = $widget_obj['params'][0]['number'];
    if (isset($widget_opt[$widget_num]['custom_class']) && !empty($widget_opt[$widget_num]['custom_class'])) {
        $params[0]['before_widget'] = preg_replace('/class="/', 'class="' . $widget_opt[$widget_num]['custom_class'] . ' ', $params[0]['before_widget'], 1);
    }
    return $params;
}

add_action('in_widget_form', 'cwc_add_custom_class_field', 10, 3);
add_filter('widget_update_callback', 'cwc_save_custom_class_field', 10, 3);
add_filter('dynamic_sidebar_params', 'cwc_add_custom_class_to_widget');
?>
