<?php
/**
* Create custom text widget designed to be displayed on mobile as well
*/
class Mobile_Text_Widget extends WP_Widget {

    /**
    * Register widget with Wordpress
    */
    function __construct() {
        $widget_ops = array(
        'classname' => 'Mobile_Text_Widget',
        'description' => 'Exactly like a text widget except will be visible when theme reduces to mobile.'
        );

        parent::__construct('Mobile_Text_Widget', 'Text Widget', $widget_ops);
    }

    /**
    * Front-end display of widget
    *
    * @see WP_Widget::widget()
    * @param array $args Widget arguments
    * @param array $instance Saved values from database
    *
    */
    public function widget( $args, $instance) {

        extract( $args, EXTR_SKIP );

        echo $before_widget;
        
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        $text = $instance['text'];
        echo $before_title . $title . $after_title;
        echo $text;
        echo $after_widget;
    }

    /**
    * @see WP_Widget::form()
    * Backend widget form
    * @param $instance Previously saved values from database
    */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ));
        $title = $instance['title'];
        $text = $instance['text'];

        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo esc_attr($title); ?>" /></label></p>
        <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_attr($text) ?> </textarea>
    <?php
    }

    /**
     * Sanitize widget values as they are saved
     * @param $new_instance
     * @param $old_instance
     */
    public function update( $new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
        $instance['text'] = $new_instance['text'];
        return $instance;
    }
}

/**
 * Register widget
 */

function register_mobile_text_widget() {
    register_widget( 'Mobile_Text_Widget');
}

add_action( 'widgets_init', 'register_mobile_text_widget');