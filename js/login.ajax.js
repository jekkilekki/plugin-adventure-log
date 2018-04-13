(function($) {

  // Show the login dialog box on click
  $( 'a#alog_show_login' ).on( 'click', function(e) {
    $( 'body' ).prepend( '<div class="login_overlay"></div>' );
    $( 'form#alog-login' ).fadeIn(500);
    $( 'div.login_overlay, form#alog-login a.close' ).on( 'click', function(e) {
      e.preventDefault();
      $( 'div.login_overlay' ).remove();
      $( 'form#alog-login' ).hide();
    });
    e.preventDefault();
  });

  // Perform AJAX login on form submit
  $( 'form#alog-login' ).on( 'submit', function(e) {
    $( 'form#alog-login p.status' ).show().text( ajax_login_object.message );
    $.ajax({
      type: 'POST',
      dataType: 'json',
      url: ajax_login_object.ajax_url,
      data: {
        'action':   'ajaxlogin', // calls wp_ajax_nopriv_ajaxlogin
        'username': $( 'form#alog-login #username' ).val(),
        'password': $( 'form#alog-login #password' ).val(),
        'security': $( 'form#alog-login #security' ).val()
      },
      success: function( data ) {
        $( 'form#alog-login p.message' ).text( data.message );
        if ( data.loggedin == true ) {
          document.location.href = ajax_login_object.redirect_url;
        }
      }
    });
    e.preventDefault();
  });
}) (jQuery);