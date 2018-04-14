(function($) {

  // REST API needs 3 things:
  // Root URL
  // Nonce value
  // Current Post / Page ID
  console.info( "REST API root: ", WP_API_settings.root );
  console.info( "Nonce value: ", WP_API_settings.nonce );
  console.info( "Post ID: ", WP_API_settings.current_ID );
  console.info( "New Post: ", WP_API_settings.new_post );

  // Create a $POST_ID variable to keep track of the current post ID
  // If we're on a post, it'll be set to the Post ID passed in from PHP.
  // If we're creating a NEW post, it'll be empty, but reset to the ID from the JSON in our Ajax response.
  let $POST_ID = WP_API_settings.current_ID;

  // Create constants for Post title and Post content we can reference later
  let $POST_TITLE = $('.entry-title').first();
  // if ( $POST_ID != '' ) 
  //   $POST_TITLE = $( '.post-' + $POST_ID + ' .entry-title' );
  let $POST_CONTENT = $('.entry-content').first();
  // if ( $POST_ID != '' )
  //   $POST_CONTENT = $( '.post-' + $POST_ID + ' .entry-content');
  let $MESSAGE_BOX = $( '.post-' + $POST_ID + ' .alog-entry-message' );

  let $POST_META = $( '.alog-post-edit-meta' );

  // Add some styling for our editor fields
  $POST_TITLE.focus( function() {
    $POST_TITLE.css( 'border', '1px solid gainsboro' );
  });
  $POST_CONTENT.focus( function() {
    $POST_CONTENT.css( 'border', '1px solid gainsboro' );
  });
  $POST_TITLE.focusout( function() {
    $POST_TITLE.css( 'border', '1px dashed black' );
  });
  $POST_CONTENT.focusout( function() {
    $POST_CONTENT.css( 'border', '1px dashed black' );
  });

  if ( $POST_CONTENT == '' ) {
    $POST_CONTENT.css( 'height', '50vh' );
  }

  // Default 'editing' is FALSE until the button is clicked
  let $EDITING = false;
  if ( WP_API_settings.current_ID == '' || WP_API_settings.new_post ) {
    $EDITING = true;
  } 

  /**
   * POSTs the new Post title and/or new Post content to the WP REST API to save it to the database
   * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
   * 
   * @param text new_title 
   * @param html new_content 
   */
  function runAjaxSave( new_title, new_content ) {
    // alert( "new title: " + new_title );
    // alert( "new content: " + new_content );
    var restUrl = WP_API_settings.root + 'wp/v2/alog/';
    if ( $POST_ID != '' && WP_API_settings.new_post != 1 ) {
      restUrl = WP_API_settings.root + 'wp/v2/alog/' + $POST_ID;
      console.info( "Rest URL: ", restUrl );
    }

    var restData = {
      // '_nonce': WP_API_settings.nonce,
      'title': new_title,
      'content': new_content,
      'status': 'publish',
    };

    if ( $( '#alog-img-id' ).val() != '' )
      restData.featured_media = $( '#alog-img-id' ).val();

    $.ajax({
      url: restUrl,
      method: 'POST',
      beforeSend: function(xhr) {
        xhr.setRequestHeader( 'X-WP-Nonce', WP_API_settings.nonce );
      },
      data: restData
    }).success( function( response ) {
      // console.log( response );
      console.log( response.id );
        $POST_ID = response.id;
        $MESSAGE_BOX.text( 'Log saved.' );
        // geturl();

        // Hide any New Post or Edit Post stuff
        $( '.alog-log-caption' ).text( 'Edit Log' ).hide();
        $( '.alog-image-input' ).hide();
        $( '.alog-tag-input' ).hide();

        // Redirect
        location.href = geturl();
    }).fail( function( response ) {
      console.log( response );
    });
  }

  function geturl() {
    var url = window.location.href;
    return url.replace( '/?new=true', '/');
  }

  /**
   * Handler for when the Post edit button is clicked
   * 
   * So far, this is the button class in TwentySeventeen
   * needs checking (or a new button) to handle more themes
   */
  $( '.post-edit-link' ).click( function(e) {

    // Prevent WordPress from opening the backend editor
    e.preventDefault();

    // Grab original Post title text and Post content HTML
    let $original_title = $POST_TITLE.text();
    let $original_content = $POST_CONTENT.html();

    // if editing post, SAVE it
    if ( $EDITING ) {

      let $new_title = $POST_TITLE.text();
      if ( $new_title == '' ) {
        var currentDate = new Date();
        $new_title = currentDate.toDateString();
      }

      let $new_content = $POST_CONTENT.html();
      // if ( $new_content == '' ) {
      //   $MESSAGE_BOX.text( 'No post content. Nothing saved.' );
      //   return;
      // } else {
      //   $new_content = $POST_CONTENT.html();
      // }

      // Save new data to the database
      runAjaxSave( $new_title, $new_content );

      // Reset Post title and Post content areas to non-editable
      $POST_TITLE.prop( 'contenteditable', 'false' );
      $POST_TITLE.css( 'border', '1px solid transparent' );
      $POST_CONTENT.prop( 'contenteditable', 'false' );
      $POST_CONTENT.css( 'border', '1px solid transparent' );

      $POST_META.hide();

      // $MESSAGE_BOX.text( $MESSAGE );

      // Change the "Save" button text back to "Edit"
      $(this).text( 'Edit' );

      // Reset the editing flag to FALSE
      $EDITING = false;

    }

    // else, EDIT it
    // https: //developer.mozilla.org/en-US/docs/Web/Guide/HTML/Editable_content
    else {

      // Set Post title box to be editable, and provide some handy CSS to let us know
      $POST_TITLE.prop( 'contenteditable', 'true' );
      $POST_TITLE.css( 'border', '1px dashed black' );

      // Set Post content box to be editable, and provide some handy CSS to let us know
      $POST_CONTENT.prop( 'contenteditable', 'true' );
      $POST_CONTENT.css( 'border', '1px dashed black' );

      $POST_META.show();

      // Change the "Edit" button text to "Save"
      $(this).text( 'Save' );

      // Set the editing flag to TRUE
      $EDITING = true;

    }

  });

})(jQuery);