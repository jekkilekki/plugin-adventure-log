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
}