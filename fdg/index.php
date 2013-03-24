<?php
	$strPageTitle = "Massive Report > Data > Browse Games";
	include_once('/includes/block_header.php');

	$sql = "SELECT date_format(MatchTime,'%c/%e/%Y') AS MatchDate, h.teamname, a.teamname, t.id, t.MatchType, if(HteamID = 11, a.team3ltr, concat('@ ',h.team3ltr)	) AS Opp, Concat(HScore,' - ',Ascore) AS Score ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE HteamID = 11 OR AteamID = 11 ";
	$sql .= "ORDER BY MatchTime ASC";

	$games = mysql_query($sql, $connection) or die(mysql_error());

	$sql = "SELECT t.id, MatchType ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID ";
	$sql .= "WHERE HteamID = 11 OR AteamID = 11 ";
	$sql .= "GROUP BY t.id ";
	$sql .= "ORDER BY MatchType ASC";

	$competitions = mysql_query($sql, $connection) or die(mysql_error());

?>
  <h1>Browse Games</h1>
  <p>Welcome to the interactive schedule for the Columbus Crew. You can filter the games below according to several criteria.</p>
  <section id="options" class="clearfix">
    <div class="option-combo control">
      <h2>Competition</h2>
      <ul id="filters" class="filter option-set clearfix" data-option-key="filter">
		<li><a href="#filter" data-option-value="*" class="selected">Show All</a></li>
<?php
	while($row = @mysql_fetch_array($competitions,MYSQL_ASSOC)) { ?>
		<li><a href="#filter" data-option-value=".g<?php echo $row['id']; ?>"><?php echo $row['id']; ?></a></li>
<?php
	}
?>
  	  </ul>
	</div>
  </section> <!-- #options -->
  <div id="container" class="clearfix">

<?php
	while($row = @mysql_fetch_array($games,MYSQL_ASSOC)) {
?>
    <div class="element g<?php echo $row['id']; ?>" data-symbol="<?php echo $row['Opp']; ?>" data-category="transition">
      <p class="number"><?php echo $row['MatchType']; ?></p>
      <h3 class="symbol"><?php echo $row['Opp']; ?></h3>
      <h2 class="name"><?php echo $row['Score']; ?></h2>
      <p class="weight"><?php echo $row['MatchDate']; ?></p>
    </div>
<?php
	}
?>

  </div> <!-- #container -->

  <script>
    $(function(){

      var $container = $('#container');

      $container.isotope({
        layoutMode: 'cellsByRow',
        cellsByRow: {
          columnWidth: 120
        },
        itemSelector : '.element',
        getSortData : {
          symbol : function( $elem ) {
            return $elem.attr('data-symbol');
          },
          category : function( $elem ) {
            return $elem.attr('data-category');
          },
          number : function( $elem ) {
            return $elem.find('.number').text();
            // return parseInt( $elem.find('.number').text(), 10 );
          },
          weight : function( $elem ) {
            return parseFloat( $elem.find('.weight').text().replace( /[\(\)]/g, '') );
          },
          name : function ( $elem ) {
            return $elem.find('.name').text();
          },
        }
      });


      var $optionSets = $('#options .option-set'),
          $optionLinks = $optionSets.find('a');

      $optionLinks.click(function(){
        var $this = $(this);
        // don't proceed if already selected
        if ( $this.hasClass('selected') ) {
          return false;
        }
        var $optionSet = $this.parents('.option-set');
        $optionSet.find('.selected').removeClass('selected');
        $this.addClass('selected');

        // make option object dynamically, i.e. { filter: '.my-filter-class' }
        var options = {},
            key = $optionSet.attr('data-option-key'),
            value = $this.attr('data-option-value');
        // parse 'false' as false boolean
        value = value === 'false' ? false : value;
        options[ key ] = value;
        if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
          // changes in layout modes need extra logic
          changeLayoutMode( $this, options )
        } else {
          // otherwise, apply new options
          $container.isotope( options );
        }

        return false;
      });


    });
  </script>



    <p>
    	Built with <a href="http://isotope.metafizzy.co/index.html">Isotope</a>, a project by <a href="http://desandro.com">David DeSandro</a> / <a href="http://metafizzy.co">Metafizzy</a>
    </p>
<?php
  include_once('/includes/block_footer.php');
?>


