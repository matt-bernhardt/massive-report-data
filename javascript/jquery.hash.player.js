<script type="text/javascript">
$(document).ready(function(){
  $("div#region p").text("Initialized");
  $(window).hashchange( function(){
    var hash = location.hash;

    var thePlayer = $('#player').attr('class');

    var theURL = ( hash.replace( /^#/, '' ) || 'overview' );

    // Iterate over all nav links, setting the "selected" class as-appropriate.
    $('#player ul a').each(function(){
      var that = $(this);
      that[ that.attr( 'href' ) === hash ? 'addClass' : 'removeClass' ]( 'selected' );
    });
    $('#player ul li').each(function(){
      if($(this).children().hasClass('selected')){
        $(this).addClass('ui-tabs-selected ui-state-active');
      } else {
        $(this).removeClass('ui-tabs-selected ui-state-active');
      }
    });

    $.ajax({
      url: '/ajax/player_'+theURL+'.php?player='+thePlayer,
      success: function(data) {
        $('div#region').html(data);
      }
    });
  
  })
  $(window).hashchange();
});
</script>