(function($) {
  $( document ).ready(function() {
    alert( "Loading TinyMCE prime" );
    tinymce.init({
      selector: "alog-tinymce-prime"
      mode : "exact",
      elements : 'pre-details',
      theme: "modern",
      skin: "lightgray",
      menubar : false,
      statusbar : false,
      toolbar: [
          "bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | undo redo"
      ],
      plugins : "paste",
      paste_auto_cleanup_on_paste : true,
      paste_postprocess : function( pl, o ) {
          o.node.innerHTML = o.node.innerHTML.replace( /&nbsp;+/ig, " " );
      }
    });
  });
}) (jQuery);