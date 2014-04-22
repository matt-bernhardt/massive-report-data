<script language="javascript" type="text/javascript">
$(function () {
	var i = 0;
var datasets = {
<?php
	$intX = 1;
	$intSeason = 0;
	$boolFirstTeam = true;

	// For each team in MLS...
	$sql = "SELECT DISTINCT HTeamID, t.Team3Ltr ";
	$sql .= "FROM tbl_games g ";
	$sql .= "LEFT OUTER JOIN tbl_teams t ON g.HTeamID = t.ID ";
	$sql .= "WHERE MatchTypeID = 21 AND YEAR(MatchTime) = 2014 ";
	$sql .= "ORDER BY Team3Ltr ASC";
	$teams = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	while($row = @mysqli_fetch_array($teams, MYSQLI_ASSOC)) {
	
		//if this isn't the first team, then print a comma to start
		if($boolFirstTeam) {
			$boolFirstTeam = FALSE;
		} else {
			print ",\r";
		}
	
		$intTeamID = $row['HTeamID'];
		$strTeamAbbv = $row['Team3Ltr'];
		if($intTeamID == 11){
			$strSeriesName = "2014";
		} else {
			$strSeriesName = $strTeamAbbv;
		}

		//open series
		print "\"".$strSeriesName."\": {label: \"".$strTeamAbbv."\", data: [";
		
		// ...for each game they play...
		$sql = "SELECT DATE_FORMAT(MatchTime,'%Y') AS Season, DATE_FORMAT(MatchTime,'%c/%e/%Y') AS MatchDate, DAYOFYEAR(MatchTime) AS MatchDayOfYear, g.HTeamID, h.team3ltr AS HomeTeam, HScore, a.team3ltr AS AwayTeam, AScore ";
		$sql .= "FROM tbl_games g ";
		$sql .= "LEFT OUTER JOIN tbl_teams h ON g.HTeamID = h.ID ";
		$sql .= "LEFT OUTER JOIN tbl_teams a ON g.ATeamID = a.ID ";
		$sql .= "WHERE (HteamID = ".$intTeamID." OR ATeamID = ".$intTeamID.") AND MatchTypeID = 21 AND MatchTime < NOW() AND YEAR(matchtime) = 2014 ";
		$sql .= "ORDER BY MatchTime ASC";
		$games = mysqli_query($connection, $sql) or die(mysqli_error($connection));
		$intX = 1;
		$intPoints = 0;
		$intWin = 0;
		$intTie = 0;
		$intLoss = 0;
		$boolFirstGame = true;
		while($row2 = @mysqli_fetch_array($games, MYSQLI_ASSOC)) {
			
			if($boolFirstGame) {
				$boolFirstGame = false;
			} else {
				print ",\r";
			}

			// Build game record
			if($row2['HTeamID']==$intTeamID){
				$strOpponent = $row2['HomeTeam'].' vs '.$row2['AwayTeam'];
				if($row2['HScore'] > $row2['AScore']) {
					$intPoints = $intPoints + 3;
					$intWin++;
					$strResult = $row2['HScore']." - ".$row2['AScore']." Win";
				} elseif($row2['HScore'] == $row2['AScore']) {
					$intPoints++;
					$intTie++;
					$strResult = $row2['HScore']." - ".$row2['AScore']." Tie";
				} else {
					$intLoss++;
					$strResult = $row2['HScore']." - ".$row2['AScore']." Loss";
				}
			} else {
				$strOpponent = $row2['AwayTeam'].' @ '.$row2['HomeTeam'];
				if($row2['AScore'] > $row2['HScore']) {
					$intPoints = $intPoints + 3;
					$intWin++;
					$strResult = $row2['HScore']." - ".$row2['AScore']." Win";
				} elseif($row2['HScore'] == $row2['AScore']) {
					$intPoints++;
					$intTie++;
					$strResult = $row2['HScore']." - ".$row2['AScore']." Tie";
				} else {
					$intLoss++;
					$strResult = $row2['HScore']." - ".$row2['AScore']." Loss";
				}
			}
			$intDayOfYear = $row2['MatchDayOfYear'];
			$strMatchDate = $row2['MatchDate'];
			$strRecord = $intWin.' W - '.$intTie.' D - '.$intLoss.' L';
			
			// Print game record
			print "[";
			if($strBy=='match'){
				print $intX.","; // game #
			} else {
				print $intDayOfYear.","; // day of year
			}
			print $intPoints.","; // accumulated points
			print "'".$strMatchDate."',"; // date
			print "'".$strOpponent."',"; // opponent
			print "'".$strRecord."',"; // record
			print "'".$strResult."'"; // result
			print "]";

			// begin

			$intX = $intX + 1;
			// end
		
		}
		
		//close series
		print "]}";
	}
	
	// final carriage return
	print "\r";
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
<?php	if($strBy=="match"){ ?>
    		max: 40,
<?php	} else { ?>
    		max: 365,
<?php 	} ?>
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
    $.each(datasets, function(key, val) {
        val.color = i;
        ++i;
    });

	// container for filters
	var filterContainter = $("#filter");

	// read initial graph state from hash
	var reqHash = window.location.hash.replace('#','');

	// populate hash array
	var d = new Date();
	var arrHash = [];
	arrHash = getHashArray(d.getFullYear());

	// set initial state based on arrHash
	i = 0;
    $.each(datasets, function(key, val) {
    	var strChecked = '';
    	if(i<=arrHash.length && key==arrHash[i]){
			strChecked = 'checked="checked" ';
			i++;
    	}

        filterContainter.append('<li><input type="checkbox" name="' + key +
                               '" '+strChecked+'id="id' + key + '">' +
                               '<label for="id' + key + '">'
                                + val.label + '</label></li>');
    });
    filterContainter.find("input").click(plotAccordingToChoices);

    function debugArray(array) {
    	for(var i = 0;i<array.length;i++){
    		alert(i+' '+array[i]);
    	}
    }

    function getHashArray(strDefault) {
		var arrTemp = [];
		var reqHash = window.location.hash.replace('#','');
		if(reqHash){
			arrTemp = reqHash.split('+');
		} else {
			arrTemp[0] = strDefault;
		}
		return arrTemp;
    }

    function setHashArray(strHash) {
    	strHash = strHash.trim().replace(/ /gi,'+');
    	window.location.hash = strHash;
    }

	// This creates/updates the chart
    function plotAccordingToChoices() {
    	var hash = '';
        var data = [];
       	$(this).parent().parent().children().children("label").removeClass('selected');

       	// scan input for current settings
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

            // build hash string for saving state
            hash+=' '+key;
        });

        setHashArray(hash);

        if (data.length > 0)
            $.plot($("#flotchart"), data, options);
    }

    plotAccordingToChoices();


    var games = [
<?php
	$intX = 1;
	while($row = @mysqli_fetch_array($attendance, MYSQLI_ASSOC)) {
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
					opponent = item.series.data[x-1][3];
					record = item.series.data[x-1][4];
					result = item.series.data[x-1][5];

				showTooltip(item.pageX, item.pageY,
							"Game " + x + ": " + date + "<br /> " + opponent +"<br />" + result + "<br />" + record
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