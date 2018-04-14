(function($) {

  // REST API needs 3 things:
  // Root URL
  // Nonce value
  // Current Post / Page ID
  console.info( "REST API root: ", WP_API_settings.root );
  console.info( "Nonce value: ", WP_API_settings.nonce );
  console.info( "Post ID: ", WP_API_settings.current_ID );
  console.info( "New Post: ", WP_API_settings.new_post );

  // $( document ).ready( function() {
  //   $( '#wp-alog_editor-editor-container .mce-statusbar .mce-resizehandle' ).before( '<span class="wordcount alog-wc-number"></span>' );
  //   $( '#tinymce' ).addClass( 'entry-content' );
  //   alert( 'Stuff : ' + $( '#tinymce' ).html() );
  // });

  // Create a $POST_ID variable to keep track of the current post ID
  // If we're on a post, it'll be set to the Post ID passed in from PHP.
  // If we're creating a NEW post, it'll be empty, but reset to the ID from the JSON in our Ajax response.
  let $POST_ID = $( '#alog-post-id' ).val();
  if ( WP_API_settings.new_post == 1 ) {
    $( '.alog-post-edit-meta' ).show();
  } else {
    $POST_ID = WP_API_settings.current_ID;
    $( '#alog-post-id' ).val( WP_API_settings.current_ID );
  }

  // Create constants for Post title and Post content we can reference later
  let $POST_TITLE = $('.entry-title').first();
  // if ( $POST_ID != '' ) 
  //   $POST_TITLE = $( '.post-' + $POST_ID + ' .entry-title' );
  let $POST_CONTENT = $('.entry-content, .mce-content-body').first();
  // let $EDITOR_CONTENT = $( '#tinymce.post-type-alog' );

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
   * "Live" word count as we type in $POST_CONTENT
   * 
   */
  var countWords = function() {
    var count = $POST_CONTENT.text();

    if ( count.length == 0 ) {
      $( '.alog-wc-number' ).html(0);
      return; 
    }

    var regex = /\s+/gi;
    var wordCount = count.trim().replace( regex, ' ' ).split( ' ' ).length;

    $( '.alog-wc-number' ).html( wordCount );
  }

  $POST_CONTENT.change(countWords);
  // $EDITOR_CONTENT.keydown(countWords);
  $POST_CONTENT.keypress(countWords);
  // $EDITOR_CONTENT.keyup(countWords);
  // $EDITOR_CONTENT.blur(countWords);
  // $EDITOR_CONTENT.focus(countWords);

  /**
   * Autosave functionality
   */
  var autosaveTimeout;

  $POST_CONTENT.keypress( function() {
    // console.info( 'Key press ID: ' + $POST_ID );
    $POST_ID = $( '#alog-post-id' ).val();
    // console.info( 'After reset: ' + $POST_ID );

    // If a timer was already started, clear it
    if ( autosaveTimeout ) clearTimeout( autosaveTimeout );

    // Set timer that will save a Log
    autosaveTimeout = setTimeout( function() {
      var $now = new Date();
      // Make ajax call to save data.
      runAjaxSave( $POST_TITLE.text(), $POST_CONTENT.html(), true );

      // @TODO: 1) Remove "Saved" after every time - fade out or something
      // 2) Convert time to a more "readable time" - another function maybe
      $( '.alog-stats-wordcount' ).html( 'Saved <span class="alog-wc-number">' + $( '.alog-wc-number' ).text() + '</span> words at ' + $now.toTimeString() );
    }, 5000 );
  });

  /**
   * POSTs the new Post title and/or new Post content to the WP REST API to save it to the database
   * @see https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/
   * 
   * @param text new_title 
   * @param html new_content 
   */
  function runAjaxSave( new_title, new_content, auto_save ) {
    // alert( "new title: " + new_title );
    // alert( "new content: " + new_content );
    var restUrl = WP_API_settings.root + 'wp/v2/alog/';
    if ( $POST_ID != '' ) {
      restUrl = WP_API_settings.root + 'wp/v2/alog/' + $POST_ID;
      console.info( "Rest URL: ", restUrl );
    }

    var restData = {
      // '_nonce': WP_API_settings.nonce,
      'title': new_title,
      'content': new_content,
    };

    if ( $( '#alog-img-id' ).val() != '' )
      restData.featured_media = $( '#alog-img-id' ).val();

    if ( auto_save ) {
      restData.status = 'draft';
    } else {
      restData.status = 'publish';
    }

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
        // $POST_ID = response.id;
        $( '#alog-post-id' ).val( response.id );

        if ( auto_save != true ) {
          // Hide any New Post or Edit Post stuff
          $( '.alog-log-caption' ).text( 'Edit Log' ).hide();
          $( '.alog-image-input' ).hide();
          $( '.alog-tag-input' ).hide();

          // Redirect
          location.href = geturl();
        }
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
      runAjaxSave( $new_title, $new_content, false );

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