<?php
add_action( 'widgets_init', 'alog_widget_goals' );
function alog_widget_goals() {
  register_widget( 'Alog_Goals_List' );
}

class Alog_Goals_List extends WP_Widget {
  // Constructor
  function __construct() {
    parent::__construct( 'alog_goals_list', 
        __( 'Alog Goals List', 'adventure-log' ), 
        array( 'description' => __( 'Keep track of your Major Goals for the year / month / week / or day.', 'adventure-log' ) ) );
  }

  // Appearance -> Widgets -> set the options
  function form( $instance ) {
    // Retrieve previous settings or render defaults
    $widget_title = ( ! empty( $instance['widget_title'] ) ? $instance['widget_title'] : __( 'Goals List', 'adventure-log' ) );
    ?>
    <!-- Display widget option fields -->
    <p>
      <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>">
        <?php _e( 'Title:', 'adventure-log' ); ?>
      </label>
      <input type="text" class="widefat title" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' ); ?>" value="<?php echo $widget_title; ?>">
    </p>
    <?php
  }

  // Validate and save widget options
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    $instance['widget_title'] = strip_tags( $instance['widget_title'] );

    return $instance;
  }

  // FRONTEND display of the widget
  function widget( $args, $instance ) {
    extract( $args );
    $title = ( ! empty( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : '' );

    echo $before_widget;
    if ( ! empty( $title ) ) {
      echo $before_title . $title . $after_title;
    }
    _e( 'Hello, widget goals!', 'adventure-log' );
    echo $after_widget;
  }
}