<?php
	if(isset($_GET['season'])){
		$intSeason = $_GET['season'];
	} else {
		$intSeason = date('Y');
	}
	$intFile = substr($intSeason,2);
?>
<iframe id="fdg_combo" src="/fdg/crew<?php print $intFile; ?>.html"></iframe>