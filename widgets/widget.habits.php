<?php
add_action( 'widgets_init', 'alog_widget_tracking' );
function alog_widget_tracking() {
  register_widget( 'Alog_Tracking_List' );
}

class Alog_Tracking_List extends WP_Widget {
  // Constructor
  function __construct() {
    parent::__construct( 'alog_tracking_list', 
        __( 'Alog Tracking List', 'adventure-log' ), 
        array( 'description' => __( 'Track habits, weight, repetitions, or anything else with this widget.', 'adventure-log' ) ) );
  }
}