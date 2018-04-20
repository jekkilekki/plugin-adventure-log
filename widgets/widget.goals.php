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
}