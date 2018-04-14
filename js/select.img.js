/**
 * @Script: WordPress Image Selection in jQuery
 * @see https://ckmacleod.com/2017/01/05/adding-wp-media-multiple-image-selection-wordpress-plug-ins/
 * @see https://derekspringer.wordpress.com/2015/05/07/using-the-wordpress-media-loader-in-the-front-end/
 */
(function($) {

  var alog_media_upload;

  $( '#alog-image-select' ).click( function(e) {
    
    e.preventDefault();
    
    // If uploader object has been created, reopen it
    if ( alog_media_upload ) {
      alog_media_upload.open();
      return;
    }

    // Extend the wp.media object
    alog_media_upload = wp.media.frames.file_frame = wp.media({
      title: $( this ).data( 'uploader_title' ),
      button: { text: $( this ).data( 'uploader_button_text' ) },
      multiple: false // change to true to allow for multiple selection
    });

    /**
     * 
     */
    alog_media_upload.on( 'select', function() {
      var attachment = alog_media_upload.state().get( 'selection' ).first().toJSON();

      // Do something with the file here
      $( '#alog-img-id' ).val( attachment.id ); // https://codex.wordpress.org/Javascript_Reference/wp.media
      $( '#alog-image-select' ).hide();
      $( '#alog-img-preview' ).attr( 'src', attachment.url );

    });

    alog_media_upload.open();

  });

}) (jQuery);