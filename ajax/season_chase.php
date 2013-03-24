<p>This is the d3-powered Combinations force-directed graph.</p>
<?php
	if(isset($_GET['season'])){
		$intSeason = $_GET['season'];
	} else {
		$intSeason = date('Y');
	}

	include_once("../includes/block_conn_open.php");

	$sql = "SELECT DATE_FORMAT(MatchTime,'%Y') AS Season, DATE_FORMAT(MatchTime,'%c/%e/%Y') AS MatchDate, h.teamname AS HomeTeam, HScore, a.teamname AS AwayTeam, AScore ";
	$sql .= "FROM tbl_games g  ";
	$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID  ";
	$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID  ";
	$sql .= "WHERE (HteamID = 11 OR ATeamID = 11) AND MatchTypeID = 21 and MatchTime < now() ORDER BY MatchTime ASC";
	$points = mysql_query($sql, $connection) or die(mysql_error());

?>

		<section id="options">
			<h2><?php print $intSeason; ?> Points Chase</h2> 
			<ul id="filter" class="inline"></ul>
		</section>
		<div id="flotchart" class="clearboth"></div>

<script language="javascript" type="text/javascript">
$(function () {
var datasets = {
<?php
	$intX = 1;
	$intSeason = 0;
	$intFirstSeason = 1;
	while($row = @mysql_fetch_array($points, MYSQL_ASSOC)) {


		if($intSeason<>$row['Season']){
			if ($intFirstSeason==1) {
				$intFirstSeason = 0;
			} else {
				print "]},\r";
			}
			print "\"" . $row['Season'] . "\": {label: \"" . $row['Season'] . "\", data: [";
			$intX = 1;
			$intPoints = 0;
			$intWin = 0;
			$intTie = 0;
			$intLoss = 0;
		}

		if($row['HomeTeam']=='Columbus Crew') {
			$strOpponent = 'vs. '.$row['AwayTeam'];
			if($row['HScore'] > $row['AScore']) {
				$intPoints = $intPoints + 3;
				$intWin++;
			} elseif($row['HScore'] == $row['AScore']) {
				$intPoints++;
				$intTie++;
			} else {
				$intLoss++;
			}
		} else {
			$strOpponent = '@ '.$row['HomeTeam'];
			if($row['AScore'] > $row['HScore']) {
				$intPoints = $intPoints + 3;
				$intWin++;
			} elseif($row['HScore'] == $row['AScore']) {
				$intPoints++;
				$intTie++;
			} else {
				$intLoss++;
			}
		}
		$strRecord = $intWin.' - '.$intTie.' - '.$intLoss;
		if($intX > 1){
			print ",";
		}
		print "[".$intX.",".$intPoints.",'".$row['MatchDate']."','".$strOpponent."','".$strRecord."']";
		$intX = $intX + 1;
		$intSeason = $row['Season'];
	}
	print "]},\r";
?>
};

    var options = {
    	series: {
    		points: {show: true},
    		lines: {show: true}
    	},
    	grid: {
    		labelMargin: 20,
    		hoverable: true,
    		backgroundColor: "#f2f2f2"
    	},
    	xaxis: {
    		min: 0,
    		max: 36,
    		color: "#b4b4b4"
    	},
    	yaxis: {
    		min: 0,
    		max: 60,
    		color: "#b4b4b4"
    	},
    	lines: {
    		steps: true
    	}
    };

    // hard-code color indices to prevent them from shifting as
    // countries are turned on/off
    var i = 0;
    $.each(datasets, function(key, val) {
        val.color = i;
        ++i;
    });



	// insert checkboxes
	var filterContainter = $("#filter");
    $.each(datasets, function(key, val) {
        filterContainter.append('<li><input type="checkbox" name="' + key +
                               '" checked="checked" id="id' + key + '">' +
                               '<label for="id' + key + '">'
                                + val.label + '</label></li>');
    });
    filterContainter.find("input").click(plotAccordingToChoices);


	//
    function plotAccordingToChoices() {
        var data = [];
       	$(this).parent().parent().children().children("label").removeClass('selected');

        filterContainter.find("input:checked").each(function () {

            var key = $(this).attr("name");
            if (key && datasets[key]) {
                data.push(datasets[key]);
				$(this).next().addClass('selected');
				//alert(key+" selected");
			}
            //alert(key+" "+datasets[key]);
			//$(this).next().css('background-color','red');
            //alert(key);
        });

        if (data.length > 0)
            $.plot($("#flotchart"), data, options);
    }

    plotAccordingToChoices();




    var games = [
<?php
	$intX = 1;
	while($row = @mysql_fetch_array($attendance, MYSQL_ASSOC)) {
		print "[".$intX.",".$row['attendance'].",'".$row['MatchDate']."','".$row['FormatAttendance']."','".$row['Opponent']."'],";
		$intX = $intX + 1;
	}
?>
    ];



	function showTooltip(x, y, contents) {
		$('<div id="tooltip">'+contents+'</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y+5,
			left: x+5,
			padding: '5px',
			border: '5px solid #e6e6e6',
			'background-color': '#f2f2f2',
			opacity: 0.90,
			color: '#000'
		}).appendTo("body").fadeIn(200);
	};

    var previousPoint = null;
    $("#flotchart").bind("plothover", function (event, pos, item) {

		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(0),
					y = item.datapoint[1].toFixed(0),
					date = item.series.data[x-1][2],
					attendance = item.series.data[x-1][3];
					opponent = item.series.data[x-1][4];

				//alert(item.toSource());
				//alert(JSON.stringify(item.series.data[4][2], null, 2));

				showTooltip(item.pageX, item.pageY,
							"Game " + x + ": " + date + "<br /> " + attendance +"<br />" + opponent
				);
			}
		}
		else {
			$("#tooltip").remove();
			previousPoint = null;
		}
    });

});
</script>

<?php
	include_once("../includes/block_conn_close.php");
?>
