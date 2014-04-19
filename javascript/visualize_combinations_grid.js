<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$("table#grid").tooltip({
		content: function() {
			var element = $(this);
			var teamminutes = $("p.data-summary span.minutes").text();
			var minutes = element.text();
			var pairing = element.attr("data-pairing")+"<br>"+Math.round((Number(minutes)/Number(teamminutes))*100)+"% of season";

			var teamplus = $("p.data-summary span.ratefor").text();
			var plus = element.attr("data-plus");
			var dblPlusRate = Math.round((Number(minutes)/Number(plus))*10)/10;
			var plusChange = Math.round((Number(teamplus)/Number(dblPlusRate))*100);
			var plusRate = plus > 0 ? " ("+dblPlusRate+" minutes each)<br>"+plusChange+"% of team rate" : '';

			var teamminus = $("p.data-summary span.rateagainst").text();
			var minus = element.attr("data-minus");
			var dblMinusRate = Math.round((Number(minutes)/Number(minus))*10)/10;
			var minusChange = Math.round((Number(dblMinusRate)/Number(teamminus))*100);
			var minusRate = minus > 0 ? " ("+dblMinusRate+" minutes each)<br>"+minusChange+"% of team rate" : '';

			return "<p>"+pairing+"</p><p>Goals: "+plus+plusRate+"</p><p>Against: "+minus+minusRate+"</p>";
		}
	});
});
</script>