(function($) {

  function createLog() {
    $.ajax({

    });
  }

  function generateJSON() {
    let data = {
      "status": "public",
      "title": $( '.entry-title' ).text(),
      "content": $( '.entry-content' ).html()
    };
  }

  $( '.add-log-button' ).click( function(e) {

    // Prevent WordPress from opening the backend editor
    e.preventDefault();
    generateJSON();
  });

}) (jQuery);