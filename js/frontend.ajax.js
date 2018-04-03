(function($) {

  // REST API needs 3 things:
  // Root URL
  // Nonce value
  // Current Post / Page ID
  console.info( "REST API root: ", WP_API_settings.root );
  console.info( "Nonce value: ", WP_API_settings.nonce );
  console.info( "Post ID: ", WP_API_settings.current_ID );

  // Create constants for Post title and Post content we can reference later
  const $POST_TITLE = $( '.entry-title' );
  const $POST_CONTENT = $( '.entry-content' );
  
  // Create a $POST_ID variable to keep track of the current post ID
  // If we're on a post, it'll be set to the Post ID passed in from PHP.
  // If we're creating a NEW post, it'll be empty, but reset to the ID from the JSON in our Ajax response.
  let $POST_ID = WP_API_settings.current_ID;

  // Default 'editing' is FALSE until the button is clicked
  let $EDITING = false;
  if ( WP_API_settings.current_ID == '' ) {
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
    $.ajax({
      url: WP_API_settings.root + 'wp/v2/alog/' + $POST_ID,
      method: 'POST',
      beforeSend: function(xhr) {
        xhr.setRequestHeader( 'X-WP-Nonce', WP_API_settings.nonce );
      },
      data: {
        // '_nonce': WP_API_settings.nonce,
        'title': new_title,
        'content': new_content
      }
    }).success( function( response ) {
      // console.log( response );
      console.log( response.id );
        $POST_ID = response.id;
    }).fail( function( response ) {
      console.log( response );
    });
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
      let $new_content = $POST_CONTENT.html();

      // Save new data to the database
      runAjaxSave( $new_title, $new_content );

      // Reset Post title and Post content areas to non-editable
      $POST_TITLE.prop( 'contenteditable', 'false' );
      $POST_TITLE.css( 'border', '1px solid transparent' );
      $POST_CONTENT.prop( 'contenteditable', 'false' );
      $POST_CONTENT.css( 'border', '1px solid transparent' );

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
      $POST_TITLE.focus( function() {
        $POST_TITLE.css( 'border', '1px solid gainsboro' );
      });
      $POST_TITLE.focusout( function() {
        $POST_TITLE.css( 'border', '1px dashed black' );
      })

      // Set Post content box to be editable, and provide some handy CSS to let us know
      $POST_CONTENT.prop( 'contenteditable', 'true' );
      $POST_CONTENT.css( 'border', '1px dashed black' );
      $POST_CONTENT.focus( function() {
        $POST_CONTENT.css( 'border', '1px solid gainsboro' );
      });
      $POST_CONTENT.focusout( function() {
        $POST_CONTENT.css( 'border', '1px dashed black' );
      });

      // Change the "Edit" button text to "Save"
      $(this).text( 'Save' );

      // Set the editing flag to TRUE
      $EDITING = true;

    }

  });

})(jQuery);