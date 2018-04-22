<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'alog_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category Adventure_Log
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */

if ( file_exists( dirname( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/cmb2/init.php';
} elseif ( file_exists( dirname( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '/CMB2/init.php';
}

/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function alog_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function alog_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}

/**
 * Manually render a field.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 */
function alog_render_row_cb( $field_args, $field ) {
	$classes     = $field->row_classes();
	$id          = $field->args( 'id' );
	$label       = $field->args( 'name' );
	$name        = $field->args( '_name' );
	$value       = $field->escaped_value();
	$description = $field->args( 'description' );
	?>
	<div class="custom-field-row <?php echo $classes; ?>">
		<p><label for="<?php echo $id; ?>"><?php echo $label; ?></label></p>
		<p><input id="<?php echo $id; ?>" type="text" name="<?php echo $name; ?>" value="<?php echo $value; ?>"/></p>
		<p class="description"><?php echo $description; ?></p>
	</div>
	<?php
}

/**
 * Manually render word count column display.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 */
function alog_display_word_count_column( $field_args, $field ) {
	?>
	<div class="custom-column-display <?php echo $field->row_classes(); ?>">
		<p><?php echo $field->escaped_value(); ?></p>
		<p class="description"><?php echo $field->args( 'description' ); ?></p>
	</div>
	<?php
}

/**
 * Manually render a field column display.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 */
function alog_display_text_small_column( $field_args, $field ) {
	?>
	<div class="custom-column-display <?php echo $field->row_classes(); ?>">
		<p><?php echo $field->escaped_value(); ?></p>
		<p class="description"><?php echo $field->args( 'description' ); ?></p>
	</div>
	<?php
}

/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function alog_register_log_metabox() {
	$prefix = 'alog_log_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_alog = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Log Data', 'cmb2' ),
		'object_types'  => array( 'alog', ), // Post type
		// 'show_on_cb' => 'alog_show_if_front_page', // function should return a bool value
		'context'    => 'side',
		'priority'   => 'low',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		'closed'     => true, // true to keep the metabox closed by default
		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
		// 'classes_cb' => 'alog_add_some_classes', // Add classes through a callback.
	) );

	$cmb_alog->add_field( array(
		'name' => __( 'Word Count', 'cmb2' ),
		'desc' => __( 'Updates on Save', 'cmb2' ),
		'id'   => $prefix . 'textsmall',
		'type' => 'text_small',
		'column' => array(
			'name'     => __( 'Word Count', 'cmb2' ), // Set the admin column title
			'position' => 2, // Set as the second column.
    ),
		'display_cb' => 'alog_display_word_count_column', // Output the display of the column values through a callback.
  ) );
  
}

add_action( 'cmb2_admin_init', 'alog_register_daily_tasks_metabox' );
/**
 * Hook in and add a metabox to demonstrate repeatable grouped fields
 */
function alog_register_daily_tasks_metabox() {
	$prefix = 'alog_daily_';

	/**
	 * Repeatable Field Groups
	 */
	$cmb_tasks = new_cmb2_box( array(
		'id'           => $prefix . 'metabox',
		'title'        => __( 'Today\'s Tasks', 'cmb2' ),
		'object_types' => array( 'alog', ),
	) );

	// $group_field_id is the field id string, so in this case: $prefix . 'demo'
	$group_field_id = $cmb_tasks->add_field( array(
		'id'          => $prefix . 'demo',
		'type'        => 'group',
		'description' => __( 'Keep track of what you did or still need to do today.', 'cmb2' ),
		'options'     => array(
			'group_title'   => __( 'Task {#}', 'cmb2' ), // {#} gets replaced by row number
			'add_button'    => __( 'Add Another Task', 'cmb2' ),
			'remove_button' => __( 'Remove Task', 'cmb2' ),
			'sortable'      => true, // beta
			// 'closed'     => true, // true to have the groups closed by default
		),
	) );

	/**
	 * Group fields works the same, except ids only need
	 * to be unique to the group. Prefix is not needed.
	 *
	 * The parent field's id needs to be passed as the first argument.
	 */
	$cmb_tasks->add_group_field( $group_field_id, array(
		'name'       => __( 'Short name', 'cmb2' ),
		'id'         => 'title',
		'type'       => 'text',
		// 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
	) );

	$cmb_tasks->add_group_field( $group_field_id, array(
		'name'        => __( 'Description', 'cmb2' ),
		'description' => __( 'Write a short description for this task (optional)', 'cmb2' ),
		'id'          => 'description',
		'type'        => 'textarea_small',
  ) );
  
  $cmb_tasks->add_group_field( $group_field_id, array(
		'name' => __( 'Completed', 'cmb2' ),
		// 'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $prefix . 'checkbox',
		'type' => 'checkbox',
	) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name' => __( 'Entry Image', 'cmb2' ),
	// 	'id'   => 'image',
	// 	'type' => 'file',
	// ) );

	// $cmb_group->add_group_field( $group_field_id, array(
	// 	'name' => __( 'Image Caption', 'cmb2' ),
	// 	'id'   => 'image_caption',
	// 	'type' => 'text',
	// ) );

}

add_action( 'cmb2_admin_init', 'alog_register_quest_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function alog_register_quest_metabox() {
	$prefix = 'alog_quest_';

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_aquest = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Quest Data', 'cmb2' ),
		'object_types'  => array( 'aquest', ), // Post type
		// 'show_on_cb' => 'alog_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
		// 'classes_cb' => 'alog_add_some_classes', // Add classes through a callback.
	) );

	$cmb_aquest->add_field( array(
		'name'             => __( 'Quest Type', 'cmb2' ),
		'desc'             => __( '(optional)', 'cmb2' ),
		'id'               => $prefix . 'radio_inline',
		'type'             => 'radio_inline',
		// 'show_option_none' => 'No Selection',
		'options'          => array(
      'task'     => __( 'Task', 'cmb2' ),
			'goal'     => __( 'Goal', 'cmb2' ),
			'habit'    => __( 'Habit', 'cmb2' ),
			'routine'  => __( 'Routine', 'cmb2' ),
		),
	) );
  
}
