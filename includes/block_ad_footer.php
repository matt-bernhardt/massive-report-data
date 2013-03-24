<?php
	$strDebug = FALSE;

	if ($strHost == 'localhost' || $strDebug) {
?>
<div class="ad_leaderboard">This would be the ad.</div>
<?php
	} else {
?>

<script type="text/javascript"><!--
google_ad_client = "ca-pub-6070488433813160";
var theWidth = $(document).width();
switch(true)
{
case (theWidth <= 580): 
	/* Small Square Footer */
	google_ad_slot = "0380911343";
	google_ad_width = 200;
	google_ad_height = 200;
	break;
case (theWidth <= 980): 
	/* Banner Footer */
	google_ad_slot = "1477345654";
	google_ad_width = 468;
	google_ad_height = 60;
	break;
default: 
	/* Leaderboard Footer */
	google_ad_slot = "4336703467";
	google_ad_width = 728;
	google_ad_height = 90;
}
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>

<?php
	}
?>
