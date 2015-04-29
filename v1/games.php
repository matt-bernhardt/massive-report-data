<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Massive Report Data > Games</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mrdata.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body style="padding-top: 51px;">
    <?php include_once("inc/ga.php"); ?>

    <?php include_once("inc/menu.php"); ?>

    <div class="container">

      <div class="starter-template">
        <h1>Games</h1>
        <p class="lead">Regular season, playoffs, open cup, and international competitions. Find every official game of the Columbus Crew here.</p>
        <div id="content"></div>
    <script type="text/jsx">

var GameDisplay = React.createClass({
  componentDidMount: function() {
    $.ajax({
      url: this.props.url,
      dataType: 'json',
      success: function(data) {
        this.setState({data: data});
      }.bind(this),
      error: function(xhr, status, err) {
        console.error(this.props.url, status, err.toString());
      }.bind(this)
    });
  },
  getInitialState: function() {
    return {
      data: [],
      filter: {
        MatchTime: "",
        MatchType: "All",
        Opponent: ""
      },
      sort: {
        criteria: "MatchTime",
        direction: "ascending"
      }
    };
  },
  handleBirthplaceFilterUpdate: function(filterBirthplaceValue) {
    tempFilter = this.state.filter;
    tempFilter.birthplace = filterBirthplaceValue;
    this.setState({
      filter: tempFilter
    });
  },
  handleNameFilterUpdate: function(filterNameValue) {
    tempFilter = this.state.filter;
    tempFilter.name = filterNameValue;
    this.setState({
      filter: tempFilter
    });
  },
  handlePositionFilterUpdate: function(filterPositionValue) {
    tempFilter = this.state.filter;
    tempFilter.position = filterPositionValue;
    this.setState({
      filter: tempFilter
    });
  },
  handleSortUpdate: function(criteria,direction) {
    console.log("Handling sort update");
    tempSort = {
      criteria: criteria,
      direction: direction
    };
    this.setState({
      sort: tempSort
    });
  },
  resort: function(items) {
    tempCriteria = this.state.sort.criteria;
    items.sort(function(a,b) {
      var propA = a[tempCriteria],
          propB = b[tempCriteria];
      if (propA < propB) return -1;
      if (propA > propB) return 1;
      // last step of sort is always by match time
      return a.MatchTime < b.MatchTime ? -1 : 1;
    });
    return items;
  },
  render: function() {
    var displayedItems = this.state.data;
    displayedItems = this.resort(displayedItems);
    return (
      <table className="table">
        <DisplayHeader updateSort={this.handleSortUpdate} />
        <DisplayBody data={displayedItems}/>
      </table>
    );
  }
});

var DisplayHeader = React.createClass({
  handleSort: function(button) {
    var tempCriteria = button.target.value;
    var tempOrder = button.target.getAttribute("data-direction");
    console.log(tempCriteria + " " + tempOrder);
    this.props.updateSort(tempCriteria,tempOrder);
  },
  render: function() {
    return (
      <thead>
        <tr>
          <th className="col-xs-2" scope="col">
            <div>
              <label htmlFor="inputMatchTime" className="control-label">Match Time</label>
            </div>
            <div className="input-group">
              <input className="form-control" id="inputMatchTime" type="text" placeholder="Match Time" />
              <span className="input-group-addon sort"><button ref="matchTimeSort" onClick={this.handleSort} value="MatchTime" data-direction="ascending">Sort</button></span>
            </div>
          </th>
          <th className="col-xs-2" scope="col">
            <div>
              <label htmlFor="inputCompetition" className="control-label">Competition</label>
            </div>
            <div className="input-group">
              <select className="form-control" id="inputCompetition">
                <option value="">All Competitions</option>
                <option value="MLS League">Regular Season</option>
                <option value="MLS Playoffs">Playoffs</option>
                <option value="US Open Cup">Open Cup</option>
                <option value="Forward">International</option>
              </select>
            </div>
          </th>
          <th className="col-xs-3" scope="col">
            <div><label htmlFor="opponentSort" className="control-label">Opponent</label></div>
            <div>
              <button className="btn btn-default col-xs-12" ref="opponentSort" onClick={this.handleSort} value="HomeTeam" data-direction="ascending">Sort</button>
            </div>
          </th>
          <th className="col-xs-1"  scope="col">
            <div>
              <label htmlFor="inputResult" className="control-label">Result</label>
            </div>
            <div>
              <button className="btn btn-default col-xs-12" ref="resultSort" onClick={this.handleSort} value="HomeScore" data-direction="descending">Sort</button>
            </div>
          </th>
          <th className="col-xs-2" scope="col">
            Venue<br />
            <button className="btn btn-default" ref="venueSort" onClick={this.handleSort} value="VenueName" data-direction="ascending">Sort</button>
          </th>
          <th className="col-xs-2" scope="col">
            Temperature<br />
            <button className="btn btn-default" ref="temperatureSort" onClick={this.handleSort} value="MeanTemperature" data-direction="ascending">Sort</button>
          </th>
        </tr>
      </thead>
    );
  }
});

var DisplayBody = React.createClass({
  render: function() {
    var gameRows = this.props.data.map(function (games) {
      return (
        <DisplayRow data={games} />
      );
    });
    return (
      <tbody className="DisplayBody">
        {gameRows}
      </tbody>
    );
  }
});

var DisplayRow = React.createClass({
  render: function() {
    var matchTimeOptions = {
      weekday: "long",
      year: "numeric",
      month: "short",
      day: "numeric",
      hour: "2-digit",
      minute: "2-digit"
    };
    var temp = Date.parse(this.props.data.MatchTime);
    var matchTime = new Date(temp).toLocaleString("en-us", matchTimeOptions);
    var matchType = this.props.data.MatchType;
    var opponent = this.props.data.AwayTeam === "Columbus Crew" ? "@ " + this.props.data.HomeTeam : "v " + this.props.data.AwayTeam;
    var result = this.props.data.HomeScore + " - " + this.props.data.AwayScore;
    var venueName = this.props.data.VenueName;
    var temperature = this.props.data.MeanTemperature;
    return (
      <tr>
        <td>{matchTime}</td>
        <td>{matchType}</td>
        <td>{opponent}</td>
        <td>{result}</td>
        <td>{venueName}</td>
        <td>{temperature}</td>
      </tr>
    );
  }
});

React.render(
  <GameDisplay url="/api/v1/games/team/11" />,
  document.getElementById('content')
);
</script>

      </div>

    </div><!-- /.container -->


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="//fb.me/react-0.13.1.js"></script>
    <script src="//fb.me/JSXTransformer-0.13.1.js"></script>
  </body>
</html>