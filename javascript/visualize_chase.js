<script language="javascript" type="text/javascript">
$(function () {
	var i = 0;
var datasets = {
<?php
	$intPoints = 0;
	$intWin = 0;
	$intTie = 0;
	$intLoss = 0;
	$intX = 1;
	$intSeason = 0;
	$intFirstSeason = 1;
	while($row = @mysqli_fetch_array($points, MYSQLI_ASSOC)) {


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
				$strResult = $row['HScore']." - ".$row['AScore']." Win";
			} elseif($row['HScore'] == $row['AScore']) {
				$intPoints++;
				$intTie++;
				$strResult = $row['HScore']." - ".$row['AScore']." Tie";
			} else {
				$intLoss++;
				$strResult = $row['HScore']." - ".$row['AScore']." Loss";
			}
		} else {
			$strOpponent = '@ '.$row['HomeTeam'];
			if($row['AScore'] > $row['HScore']) {
				$intPoints = $intPoints + 3;
				$intWin++;
				$strResult = $row['HScore']." - ".$row['AScore']." Win";
			} elseif($row['HScore'] == $row['AScore']) {
				$intPoints++;
				$intTie++;
				$strResult = $row['HScore']." - ".$row['AScore']." Tie";
			} else {
				$intLoss++;
				$strResult = $row['HScore']." - ".$row['AScore']." Loss";
			}
		}

		if($row['HScore']>$row['AScore']){
		} else if($row['HScore']==$row['AScore']) {
			$strResult = "(".$row['HScore']." - ".$row['AScore']." Tie)";
		} else {
		}

		$strRecord = $intWin.' W - '.$intTie.' D - '.$intLoss.' L';
		if($intX > 1){
			print ",";
		}
		
		$intDayOfYear = $row['MatchDayOfYear'];

		print "[";
		if($strBy=="match"){
			print $intX;
		} else {
			print $intDayOfYear;
		}
		print ",".$intPoints.",'".$row['MatchDate']."','".$strOpponent."','".$strRecord."','".$strResult."']";
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
    var i = 0;
    $.each(datasets, function(key, val) {
        val.color = i;
        ++i;
    });

	// container for filters
	var filterContainter = $("#filter");

	// read initial state from hash into arrHash
	var reqHash = window.location.hash.replace('#','');

	// populate hash array
	var d = new Date();
	var arrHash = [];
	// arrHash = getHashArray(d.getFullYear());
	arrHash = getHashArray(<?php echo $intThisYear; ?>);

	// set initial state based on arrHash
	i = 0;
    $.each(datasets, function(key, val) {
    	var strChecked = '';
    	if(i<=arrHash.length && key==arrHash[i]){
    		strChecked = 'checked="checked"';
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

	//
    function plotAccordingToChoices() {
    	var hash = '';
        var data = [];
       	$(this).parent().parent().children().children("label").removeClass('selected');

        filterContainter.find("input:checked").each(function () {

            var key = $(this).attr("name");
            if (key && datasets[key]) {
                data.push(datasets[key]);
				$(this).next().addClass('selected');
			}

            hash += ' '+key;

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