<?php
    $strPageTitle = "Massive Report > Data > Standings";
    $strMenuGroup = "Standings";

    include_once("includes/block_conn_open.php");

?>
<script type="text/javascript">
$(function () {

	var east = 
<?php
	$arrEast = array();
	$arrWest = array();
	
	$sql = "SELECT id, teamname, Conference ";
	$sql .= "FROM tbl_teams ";
	$sql .= "WHERE League = 'MLS' ";
	$sql .= "ORDER BY teamname";
	$rs = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$x = 0;
	$y = 0;
	while($row = @mysqli_fetch_array($rs, MYSQLI_ASSOC)) {
		$intTeamID = $row['id'];
		$strTeamName = $row['teamname'];
		if($row['Conference'] == "Eastern"){
			$arrEastJSON[$x]['label'] = $strTeamName;
			$arrEastJSON[$x]['color'] = '#444';
			$sql = "SELECT SUM(IF(HTeamID = ".$intTeamID.",IF(HScore>AScore,1,0),IF(AScore>HScore,1,0))) AS Wins, SUM(IF(HScore=AScore,1,0)) AS Ties, SUM(IF(HTeamID = ".$intTeamID.",IF(HScore<Ascore,1,0),IF(AScore<HScore,1,0))) AS Losses ";
			$sql .= "FROM tbl_games ";
			$sql .= "WHERE YEAR(matchTime) = 2015 AND MatchTime < NOW() AND MatchTypeID = 21 AND (HTeamID = ".$intTeamID." OR ATeamID = ".$intTeamID.")";
			$rs1 = mysqli_query($connection, $sql) or die(mysqli_error($connection));
			while($row1 = @mysqli_fetch_array($rs1, MYSQLI_ASSOC)){			
				$intGP =  $row1['Wins']+$row1['Ties']+$row1['Losses'];
				$intPoints = ($row1['Wins']*3)+$row1['Ties'];
				$intMaxPoints = $intPoints + (3*(34-$intGP));
				$arrEastJSON[$x]['data'][] = array($intPoints,10-$x,$strTeamName);
				$arrEastJSON[$x]['data'][] = array($intMaxPoints,10-$x,$strTeamName);
				$arrEastJSON[$x]['points'] = $intPoints;
			}
			$x++;
		} else {
			$arrWestJSON[$y]['label'] = $strTeamName;
			$arrWestJSON[$y]['color'] = '#444';
			$sql = "SELECT SUM(IF(HTeamID = ".$intTeamID.",IF(HScore>AScore,1,0),IF(AScore>HScore,1,0))) AS Wins, SUM(IF(HScore=AScore,1,0)) AS Ties, SUM(IF(HTeamID = ".$intTeamID.",IF(HScore<Ascore,1,0),IF(AScore<HScore,1,0))) AS Losses ";
			$sql .= "FROM tbl_games ";
			$sql .= "WHERE YEAR(matchTime) = 2015 AND MatchTime < NOW() AND MatchTypeID = 21 AND (HTeamID = ".$intTeamID." OR ATeamID = ".$intTeamID.")";
			$rs1 = mysqli_query($connection, $sql) or die(mysqli_error($connection));
			while($row1 = @mysqli_fetch_array($rs1, MYSQLI_ASSOC)){
				$intGP =  $row1['Wins']+$row1['Ties']+$row1['Losses'];
				$intPoints = ($row1['Wins']*3)+$row1['Ties'];
				$intMaxPoints = $intPoints + (3*(34-$intGP));
				$arrWestJSON[$y]['data'][] = array($intPoints,10-$y,$strTeamName);
				$arrWestJSON[$y]['data'][] = array($intMaxPoints,10-$y,$strTeamName);
				$arrWestJSON[$y]['points'] = $intPoints;
			}
			$y++;
		}
	}
	
	usort($arrEastJSON, 'sortByOrder');
	usort($arrWestJSON, 'sortByOrder');

	for($x=0;$x<count($arrEastJSON);$x++){
		if($arrEastJSON[$x]['label']=="Columbus Crew"){ 
			$strColor = '#ffd407';
		} elseif ($x<5) {
			$strColor = '#111';
		} else {
			$strColor = '#444';
		}
		$arrEastJSON[$x]['color'] = $strColor;
		$arrEastJSON[$x]['data'][0][1] = 10-$x;
		$arrEastJSON[$x]['data'][1][1] = 10-$x;
		unset($arrEastJSON[$x]['points']);
	}

	for($x=0;$x<count($arrWestJSON);$x++){
		if($arrWestJSON[$x]['label']=="Columbus Crew"){ 
			$strColor = '#ffd407';
		} elseif ($x<5) {
			$strColor = '#111';
		} else {
			$strColor = '#444';
		}
		$arrWestJSON[$x]['color'] = $strColor;
		$arrWestJSON[$x]['data'][0][1] = 10-$x;
		$arrWestJSON[$x]['data'][1][1] = 10-$x;
		unset($arrWestJSON[$x]['points']);
	}

	print json_encode($arrEastJSON);
	
?>;
var west =
<?php 
	print json_encode($arrWestJSON);
?>;

   var options = {
    	series: {
    		points: {show: true, radius: 8},
    		lines: {show: true, lineWidth: 16}
    	},
    	grid: {
    		labelMargin: 20,
    		hoverable: true,
    		backgroundColor: "#f2f2f2"
    	},
    	xaxis: {
    		min: 0,
    		max: 102,
    		colors: "#b4b4b4"
    	},
    	yaxis: {
    		min: 0,
    		max: 11,
    		color: "#b4b4b4"
    	},
		legend: {
			show: false
		}
    };
    
    $.plot($("#east"), east, options);
    $.plot($("#west"), west, options);

	
	function showTooltip(x, y, contents) {
		$('<div id="tooltip">'+contents+'</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y-15,
			left: x+15,
			padding: '5px',
			border: '5px solid #e6e6e6',
			'background-color': '#f2f2f2',
			opacity: 0.90,
			color: '#000'
		}).appendTo("body").fadeIn(200);
	};

    var previousPoint = null;
    $(".standings").bind("plothover", function (event, pos, item) {
	// alert(teams);
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(0),
					y = item.datapoint[1].toFixed(0),
					team = item.series.label;
				if (item.dataIndex==0){
					var strLabel = " current points";
				} 
				else  {
					var strLabel = " points maximum";
				}
				showTooltip(item.pageX, item.pageY,
					team + ": " + x + strLabel
				);
			}
		}
		else {
			previousPoint = null;
		}

		});
});
</script>
<?php 

function sortByOrder($a, $b) {
    return  $b['points'] - $a['points'];
}	

    include_once("includes/block_conn_close.php");
?>