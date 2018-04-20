<?php
add_action( 'widgets_init', 'alog_widget_routines' );
function alog_widget_routines() {
  register_widget( 'Alog_Routines_List' );
}

class Alog_Routines_List extends WP_Widget {
  // Constructor
  function __construct() {
    parent::__construct( 'alog_routines_list', 
        __( 'Alog Routines List', 'adventure-log' ), 
        array( 'description' => __( 'Build routines to bookend your day or work schedule.', 'adventure-log' ) ) );
  }

  // Appearance -> Widgets -> set the options
  function form( $instance ) {
    // Retrieve old options or set defaults
    $widget_title = ( ! empty( $instance['widget_title'] ) ? $instance['widget_title'] : __( 'Routines', 'adventure-log' ) );
    ?>
    <!-- Display widget options -->
    <p>
      <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>">
        <?php _e( 'Title:', 'adventure-log' ); ?>
      </label>
      <input type="text" class="widefat title" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $widget_title; ?>">
    </p>
    <?php
  }

  // Validate and save the widget options
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['widget_title'] = strip_tags( $instance['widget_title'] );

    return $instance;
  }

  // FRONTEND display of widget
  function widget( $args, $instance ) {
    extract( $args );

    $title = ( ! empty( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '' );

    echo $before_widget;
    if ( $title != '' ) {
      echo $before_title . $title . $after_title;
    }
    echo 'Routine list';
    echo $after_widget;
  }
}