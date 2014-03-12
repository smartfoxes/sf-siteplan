jQuery(document).ready(function($){
  var _custom_media = true,
      _orig_send_attachment = wp.media.editor.send.attachment;

  $('.upload_button').click(function(e) {
    var send_attachment_bkp = wp.media.editor.send.attachment;
    var button = $(this);
    var id = button.attr('id').replace('_button', '');
    _custom_media = true;
    wp.media.editor.send.attachment = function(props, attachment){
      if ( _custom_media ) {
        $("#"+id).val(attachment.url);
      } else {
        return _orig_send_attachment.apply( this, [props, attachment] );
      };
    }

    wp.media.editor.open(button);
    return false;
  });
  
  $('.siteplan-image').click(function(e) {
    var offset = $(this).offset();
    //var x = e.clientX - offset.left + parseInt($(this).css('left'));
    //var y = e.clientY - offset.top;
    
    var offset_t = $(this).offset().top - $(window).scrollTop();
    var offset_l = $(this).offset().left - $(window).scrollLeft();

    var x = Math.round( (e.clientX - offset_l  ));//parseInt($(this).css('left'))
    var y = Math.round( (e.clientY - offset_t) );
    
    $("#sf_siteplan_lot_latlng").val(x+','+y);
  });

  $('.add_media').on('click', function(){
    _custom_media = false;
  });
});