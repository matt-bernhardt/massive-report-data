<script type="text/javascript">
$(document).ready(function(){
  $("div#region p").text("Initialized");
  $(window).hashchange( function(){
    var hash = location.hash;

    var theSeason = $('#season').attr('class');

    var theURL = ( hash.replace( /^#/, '' ) || 'overview' );

    // Iterate over all nav links, setting the "selected" class as-appropriate.
    $('#season ul a').each(function(){
      var that = $(this);
      that[ that.attr( 'href' ) === hash ? 'addClass' : 'removeClass' ]( 'selected' );
    });
    $('#season ul li').each(function(){
      if($(this).children().hasClass('selected')){
        $(this).addClass('ui-tabs-selected ui-state-active');
      } else {
        $(this).removeClass('ui-tabs-selected ui-state-active');
      }
    });

    $.ajax({
      url: '/ajax/season_'+theURL+'.php?season='+theSeason,
      success: function(data) {
        $('div#region').html(data);
      }
    });
  
  })
  $(window).hashchange();
});
</script>