<script type="text/javascript">
$(function(){
	$('input').change(function() {
	  //alert('input clicked');
	});
	$('select').change(function() {
	  //alert('select option changed');
	});
	$('option').change(function() {
	  //alert('option changed');
	});
	$('div.ui-slider a').change(function() {
	  //alert('div a changed');
	});

	$('select').selectToUISlider({
		labels: 10
	});
	//fix color 
	fixToolTipColor();	
	
});
</script>