<?php
/*
Plugin Name: WYSIWYG for Text widgets 
Description: Adds a WYSIWYG editor to the WordPress text widget  
Version: 1.0
*/

/**
 * Adds a nicedit WYSIWYG editor to WordPress text widgets
 */
class Wysiwyg_Text_Wiggets {
  
  function Wysiwyg_Text_Wiggets() {
    $this->init();  
  }

  function init() {
    add_action('in_widget_form', array($this, 'set_nicedit_form_widget'), 10, 3);
    add_filter('widget_update_callback', array($this, 'set_nicedit_update_widget'), 10, 4);
    add_action( 'admin_enqueue_scripts', array($this, 'add_admin_widget_scripts') );
  }

  
  /**
   * Adds text input to text widget forms
   * 
   * @param object $obj form
   * @param string $return not used
   * @param array $instance widget instance
   */
  function set_nicedit_form_widget($obj, $return, $instance) {
    // in_widget_form action is used to call the form widget
    // if it is WP_Widget_Text shows a new input form to shows the editor in the textarea 
    if (is_a($obj, 'WP_Widget_Text')) { ?>
  		<p><input class="nicedit" id="<?php echo $obj->get_field_id('nicedit'); ?>" name="<?php echo $obj->get_field_name('nicedit'); ?>" type="checkbox" <?php checked(isset($instance['nicedit']) ? $instance['nicedit'] : 0); ?> /> <label for="<?php echo $obj->get_field_id('nicedit'); ?>"><?php _e('Utilizar editor HTML'); ?></label></p>
  <?php 
      if (isset($instance['nicedit']) && !empty($_POST)) {
        // If new checkbox is selected and widget is updated, init function is callled, showing the WYSIWIG editor
  ?>
      <script type="text/javascript">
        init();
      </script>
  <?php    
      }
    }
  }
  
  
  /**
   * Adds nicedit param to update function
   * 
   * @param array $instance widget instance
   * @param array $new_instance new widget instance
   * @param array $old_instance not used
   * @param object $obj widget object
   * @return array
   */
  function set_nicedit_update_widget($instance, $new_instance, $old_instance, $obj) {
    // If it's a Text Widget    
    if (is_a($obj, 'WP_Widget_Text')) {
      $instance["nicedit"] = isset($new_instance["nicedit"]) && $new_instance["nicedit"] == 'on';
    }
    return $instance;
  }
  
  /**
   * Adds admin scripts and styles
   */
  function add_admin_widget_scripts() {
    global $current_screen;
    // Only in the widgets screen
    if ($current_screen->base == 'widgets') {
       wp_enqueue_script( 'nicedit', 'http://js.nicedit.com/nicEdit-latest.js' );
       wp_enqueue_script( 'admin', plugins_url( '/js/admin.js', __FILE__ ) );
       wp_localize_script( 'admin', 'admin', array('path'=>get_bloginfo('template_directory')) );
       wp_enqueue_style( 'admin', plugins_url( '/css/admin.css', __FILE__ ) );
    }
  }
}

$wysiwyg_text_widget = new Wysiwyg_Text_Wiggets();