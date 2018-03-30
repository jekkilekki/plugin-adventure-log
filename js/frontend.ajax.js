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

  // $POST_CONTENT.after( '<button class="save-button save-content" style="display: hidden">Save</button>' );

  $( '.post-edit-link' ).click( function(e) {
    // Prevent WordPress from opening the backend editor
    e.preventDefault();

    // if editing post, save it
    if ( $EDITING ) {

      // $( '#title-input' ).toggle();
      // $( '#content-input' ).toggle();

      // $POST_TITLE.toggle();
      // $POST_CONTENT.toggle();

      $POST_TITLE.prop( 'contenteditable', 'false' );
      $POST_TITLE.css( 'border', '1px solid transparent' );
      $POST_CONTENT.prop( 'contenteditable', 'false' );
      $POST_CONTENT.css( 'border', '1px solid transparent' );

      $(this).text( 'Edit' );

      $EDITING = false;

    }

    // else, edit it
    // https: //developer.mozilla.org/en-US/docs/Web/Guide/HTML/Editable_content
    else {

      let $original_title = $POST_TITLE.text();
      let $original_content = $POST_CONTENT.text();

      // $POST_TITLE.toggle();
      // $POST_CONTENT.toggle();

      // $POST_TITLE.after( '<input id="title-input" type="text">' );
      // $POST_CONTENT.after( '<textarea id="content-input"></textarea>' );

      // document.querySelector( '#title-input' ).value = $original_title;
      // document.querySelector( '#content-input' ).value = $original_content;

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