<?php
	if(isset($boolIsotope) && $boolIsotope == TRUE) {
?>
    <p class="builtwith">Built with <a href="http://isotope.metafizzy.co/index.html">Isotope</a>, a project by <a href="http://desandro.com">David DeSandro</a> / <a href="http://metafizzy.co">Metafizzy</a></p>
<?php
	}
	if(isset($boolFlot) && $boolFlot == TRUE) {
?>
   	<p class="builtwith">Built with <a href="http://www.flotcharts.org/">Flot</a>, a project by <a href="http://people.iola.dk/olau/">Ole Laursen</a> / <a href="http://www.iola.dk/">IOLA</a>.</p>
<?php
	}
	if(isset($boolInputSlider) && $boolInputSlider == TRUE) {
?>
   	<p class="builtwith">Built with <a href="http://www.filamentgroup.com/lab/update_jquery_ui_slider_from_a_select_element_now_with_aria_support/">jQueryUI Slider</a>, a project by <a href="http://www.filamentgroup.com">Filament Group</a>.</p>
<?php
	}
	if(isset($boolTabs) && $boolTabs==TRUE){
?>
<script language="javascript" type="text/javascript">
		$(function() {
			$( "#tabs" ).tabs();
		});
</script>
<?php
	}
?>

<?php include "includes/block_ad_footer.php"; ?>

	  </section>
	  <footer>
	    <ul class="inline" style="float: right;">
	      <li><a href="/changelog">Changelog</a></li>
	      <li><a href="/about">About</a></li>
	    </ul>
	    <p>Massive Report Data is a production of Matthew Bernhardt for Massive Report. Please contact Matt by <a href="mailto:bernhardtsoccer@yahoo.com?subject=MRData">email</a>, <a href="http://www.facebook.com/BernhardtSoccer">Facebook</a> or <a href="http://twitter.com/bernhardtsoccer">Twitter</a> with any questions or concerns.</p>
	  </footer>
    </div>
  </body>
</html>
<?php include "includes/block_conn_close.php"; ?>