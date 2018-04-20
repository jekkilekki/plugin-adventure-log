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
}