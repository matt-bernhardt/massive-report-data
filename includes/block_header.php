<?php
	include_once "includes/block_conn_open.php";
	if ($strPageTitle == "") {
		$strPageTitle = "Massive Report > Data";
	}
?>
<!doctype html>
<html lang="en">
  <head>
    <title><?php echo $strPageTitle; ?></title>
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.5" />
    <meta name="apple-mobile-web-app-capable" content="yes" />

    <!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script type="text/javascript" src="/javascript/jquery.ba-hashchange.js"></script>
<?php
	if(isset($boolIsotope) && $boolIsotope == TRUE) {
?>
    <script src="/javascript/jquery.isotope.min.js"></script>
<?php
	}
  if(isset($boolDataTables) && $boolDataTables == TRUE) {
?>
    <script src="//cdn.datatables.net/1.10.5/js/jquery.dataTables.min.js"></script>
<?php
  }
	if(isset($boolFlot) && $boolFlot == TRUE) {
?>
	<script src="/javascript/jquery.flot.js"></script>
	<script src="/javascript/jquery.flot.resize.js"></script>
<?php
	}
  if(isset($boolInputDate) && $boolInputDate == TRUE) {
?>
  <script type="text/javascript" src="/javascript/jquery-ui-1.8.21.custom.min.js"></script>
  <script src="/javascript/selectToUISlider.jQuery.js"></script>
  <link rel="stylesheet" href="/styles/redmond/jquery-ui-1.7.1.custom.css" type="text/css" />
  <link rel="Stylesheet" href="/styles/ui.slider.extras.css" type="text/css" />  
<?php
  }
	if(isset($boolInputSlider) && $boolInputSlider == TRUE) {
?>
	<script type="text/javascript" src="/javascript/jquery-ui-1.8.21.custom.min.js"></script>
	<script src="/javascript/selectToUISlider.jQuery.js"></script>
	<link rel="stylesheet" href="/styles/redmond/jquery-ui-1.7.1.custom.css" type="text/css" />
	<link rel="Stylesheet" href="/styles/ui.slider.extras.css" type="text/css" />  
<?php
	}
	if(isset($boolTabs) && $boolTabs==TRUE){
?>
	<script src="/javascript/jquery-ui-1.8.21.custom.min.js"></script>
  <script src="/javascript/jquery.ba-bbq.js"></script>
	<link href="/styles/vader/jquery-ui-1.8.21.custom.css" rel="stylesheet" type="text/css">
<?php
	}
	if(isset($boolD3) && $boolD3==TRUE){
?>
<!-- script src="http://d3js.org/d3.v2.js" type="text/javascript"></script -->
<script src="http://d3js.org/d3.v3.min.js" type="text/javascript"></script>
<?php
	}
?>
    <link href="/styles/reset.css" rel="stylesheet" type="text/css">
    <link href="/styles/styles.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/styles/isotope.css" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
  <script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-13277032-4']);
    _gaq.push(['_trackPageview']);

    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

  </script>
  </head>
  <body>
    <a class="semantic" href="#content">Skip to Content</a>
    <div class="container">
      <header>
        <ul class="massive-network">
          <li><a href="http://www.massivereport.com">Massive Report</a></li>
          <li class="selected"><a href="/">Data</a></li>
        </ul>
        <div class="site-title"><a href="/">Data</a></div>
        <nav>
          <a class="grid-2" href="/browse/players"<?php if($strMenuGroup=="Players"){print(" class=selected");}?>>Players</a>
          <a class="grid-2" href="/date"<?php if($strMenuGroup=="Dates"){print(" class=selected");}?>>Dates</a>
          <a class="grid-2" href="/browse/games"<?php if($strMenuGroup=="Games"){print(" class=selected");}?>>Games</a>
          <a class="grid-2" href="/browse/roster"<?php if($strMenuGroup=="Roster"){print(" class=selected");}?>>Roster</a>
          <a class="grid-2" href="/visualize/attendance"<?php if($strMenuGroup=="Attendance"){print(" class=selected");}?>>Attendance</a>
          <a class="grid-2" href="/visualize/chase"<?php if($strMenuGroup=="Chase"){print(" class=selected");}?>>Chase</a>
          <a class="grid-2" href="/season"<?php if($strMenuGroup=="Seasons"){print(" class=selected");}?>>Seasons</a>
          <a class="grid-2" href="/browse/opponents"<?php if($strMenuGroup=="Opponents"){print(" class=selected");}?>>Opponents</a>
          <a class="grid-2" href="/visualize/standings"<?php if($strMenuGroup=="Standings"){print(" class=selected");}?>>Standings</a>
        </nav>
      </header>
	  <section id="content">
