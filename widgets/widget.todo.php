<?php
add_action( 'widgets_init', 'alog_widget_todo' );
function alog_widget_todo() {
  register_widget( 'Alog_To_Do_List' );
}

class Alog_To_Do_List extends WP_Widget {
  // Constructor
  function __construct() {
    parent::__construct( 'alog_to_do_list', 
        __( 'Alog To Do List', 'adventure-log' ), 
        array( 'description' => __( 'Displays list of daily to dos.', 'adventure-log' ) ) );
  }

  // Appearance -> Widgets -> set the options
  function form( $instance ) {
    // Retrieve previous values from $instance or set defaults
    // $render_widget = ( ! empty( $instance['render_widget'] ) ? $instance['render_widget'] : 'true' );
    $widget_title = ( ! empty( $instance['widget_title'] ) ? esc_attr( $instance['widget_title'] ) : __( 'Today\'s Tasks', 'adventure-log' ) );
    ?>
    <!-- Display fields for widget options -->
    <p>
      <label for="<?php echo $this->get_field_id( 'widget_title' ); ?>">
        <?php _e( 'Title:', 'adventure-log' ); ?>
      </label>
      <input type="text" class="widefat title" id="<?php echo $this->get_field_id( 'widget_title' ); ?>" name="<?php echo $this->get_field_name( 'widget_title' );?>" value="<?php echo $widget_title; ?>"> 
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
    // extract members of args array as individual variables
    extract( $args );

    $title = ( ! empty( $instance['widget_title'] ) ? apply_filters( 'widget_title', $instance['widget_title'] ) : __( 'Today\'s Tasks', 'adventure-log' ) );

    // p. 262
    // Preparation of query string to retrieve Tasks
    // $query_array = array();

    // Execution of post query
    // $task_query = new WP_Query();
    // $task_query->query( $query_array );

    // Display widget title
    echo $before_widget;
    if ( ! empty( $title ) ) {
      echo $before_title . $title . $after_title;
    }
    echo 'To do widget';

    // Check if any posts were returned by query
    // if ( $task_query->have_posts() ) {
      // Display in unordered list layout
      // echo '<ul>';

      // Cycle through all items
      // while ( $task_query->have_posts() ) : $task_query->the_post();

        // echo '<li>';
        // echo get_the_title( get_the_ID() );
        // echo '</li>';

      // endwhile;

      // echo '</ul>';
    // }

    // wp_reset_query();
    echo $after_widget;
  }
}