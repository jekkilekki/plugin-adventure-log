(function($) {

  // REST API needs 3 things:
  // Root URL
  // Nonce value
  // Current Post / Page ID

  console.info( "REST API root: ", WPsettings.root );
  console.info( "Nonce value: ", WPsettings.nonce );
  console.info( "Post ID: ", WPsettings.current_ID );

  const $POST_TITLE = $( '.entry-title' );
  const $POST_CONTENT = $( '.entry-content' );
  let $EDITING = false;

  function runAjaxSave2( new_title, new_content ) {
    $.ajax({
      url: WPsettings.root + 'wp/v2/posts/' + WPsettings.current_ID,
      method: 'POST',
      beforeSend: function(xhr) {
        // xhr.setRequestHeader( 'X-WP-Nonce', WPsettings.nonce );
        xhr.setRequestHeader( 'Authorization', 'Basic YWFyb246Zmx1ZmZoMzRk' );
      },
      data: {
        'title': new_title,
        // 'content': new_content
      },

    });
  }

  function runAjaxSave( new_title ) {
    $.ajax({
      url: WPsettings.root + 'wp/v2/posts/' + WPsettings.current_ID,
      method: 'POST',
      crossDomain: true,
      beforeSend: function(xhr) {
        xhr.setRequestHeader( 'X-WP-Nonce', WPsettings.nonce );
        // xhr.setRequestHeader( 'Authorization', 'Basic YWFyb246Zmx1ZmZoMzRk' );
      },
      data: {
        'title': new_title
      },
      success: function( response, txtStatus, xhr ) {
        console.log( response );
        console.log( xhr.status );
      },
      fail: function( response, txtStatus, xhr ) {
        console.log( response );
        console.log( xhr.status );
      }
    }).done( function( response ) {
      console.log( response );
    });
  }


  $( '.post-edit-link' ).click( function(e) {

    // Prevent WordPress from opening the backend editor
    e.preventDefault();

    let $original_title = $POST_TITLE.text();
    let $original_content = $POST_CONTENT.html();

    // if editing post, SAVE it
    if ( $EDITING ) {

      let $new_title = $POST_TITLE.text();
      let $new_content = $POST_CONTENT.html();

      // Check to be sure we edited something before running the Ajax call
      if ( $new_title != $original_title || $new_content != $original_content ) {
        if( confirm( 'Post edited. Save?' ) ) {
          runAjaxSave( $new_title/*, $new_content */ );
        }
      }

      $POST_TITLE.prop( 'contenteditable', 'false' );
      $POST_TITLE.css( 'border', '1px solid transparent' );
      $POST_CONTENT.prop( 'contenteditable', 'false' );
      $POST_CONTENT.css( 'border', '1px solid transparent' );

      $(this).text( 'Edit' );

      $EDITING = false;

    }

    // else, EDIT it
    // https: //developer.mozilla.org/en-US/docs/Web/Guide/HTML/Editable_content
    else {

      $POST_TITLE.prop( 'contenteditable', 'true' );
      $POST_TITLE.css( 'border', '1px dashed black' );
      $POST_TITLE.focus( function() {
        $POST_TITLE.css( 'border', '1px solid gainsboro' );
      });
      $POST_TITLE.focusout( function() {
        $POST_TITLE.css( 'border', '1px dashed black' );
      })

      $POST_CONTENT.prop( 'contenteditable', 'true' );
      $POST_CONTENT.css( 'border', '1px dashed black' );
      $POST_CONTENT.focus( function() {
        $POST_CONTENT.css( 'border', '1px solid gainsboro' );
      });
      $POST_CONTENT.focusout( function() {
        $POST_CONTENT.css( 'border', '1px dashed black' );
      });

      $(this).text( 'Save' );

      $EDITING = true;

    }

  });

})(jQuery);