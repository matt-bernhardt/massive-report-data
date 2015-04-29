<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Massive Report Data > Player Roster</title>

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
        <h1>Players</h1>
        <p class="lead">The following table contains every player to make an appareance in official competition for the Columbus Crew SC.</p>
        <p>
        The table below can be filtered and sorted using the control bar. It contains records for every player in team history who took part in an official competition. This includes the MLS regular season and playoffs, as well as the Open Cup and international competitions like the Champions League. It does not include preseason games, international exhibitions, or scrimmages.
        </p>
        <div id="content"></div>
    <script type="text/jsx">

var PlayerDisplay = React.createClass({
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
        name: "",
        position: "All",
        birthplace: ""
      },
      sort: {
        criteria: "LastName",
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
      // last step of sort is always by last name
      return a.LastName < b.LastName ? -1 : 1;
    });
    return items;
  },
  render: function() {
    // Apply filters to create displayedItems
    var displayedItems = this.state.data.filter(function(item) {
      var nameStatus = item.PlayerName.toLowerCase().indexOf(this.state.filter.name.toLowerCase());
      var positionStatus = this.state.filter.position === "All" ? 1 : item.Position.toLowerCase().indexOf(this.state.filter.position.toLowerCase());
      var birthplaceStatus = item.Birthplace.toLowerCase().indexOf(this.state.filter.birthplace.toLowerCase());
      var match = Math.min(
        nameStatus,
        positionStatus,
        birthplaceStatus
        );
      return (match !== -1);
    }.bind(this));
    displayedItems = this.resort(displayedItems);
    return (
      <table className="table">
        <DisplayHeader updateNameFilter={this.handleNameFilterUpdate} updateBirthplaceFilter={this.handleBirthplaceFilterUpdate} updatePositionFilter={this.handlePositionFilterUpdate} updateSort={this.handleSortUpdate} />
        <DisplayBody data={displayedItems}/>
      </table>
    );
  }
});

var DisplayHeader = React.createClass({
  handleFilterBirthplaceChange: function() {
    var value = this.refs.birthplaceFilter.getDOMNode().value;
    this.props.updateBirthplaceFilter(value);
  },
  handleFilterNameChange: function() {
    var value = this.refs.nameFilter.getDOMNode().value;
    this.props.updateNameFilter(value);
  },
  handleFilterPositionChange: function() {
    var value = this.refs.positionFilter.getDOMNode().value;
    this.props.updatePositionFilter(value);
  },
  handleSort: function(button) {
    var tempCriteria = button.target.value;
    var tempOrder = button.target.getAttribute("data-direction");
    this.props.updateSort(tempCriteria,tempOrder);
  },
  render: function() {
    return (
      <thead>
        <tr>
          <th className="col-xs-3 col-sm-4 col-md-3" scope="col">
            <div>
              <label htmlFor="inputPlayerName" className="control-label">Player Name</label>
            </div>
            <div className="input-group">
              <input className="form-control" id="inputPlayerName" type="text" ref="nameFilter" onChange={this.handleFilterNameChange} placeholder="Player Name" />
              <span className="input-group-addon sort"><button ref="nameSort" onClick={this.handleSort} value="LastName" data-direction="ascending">Sort</button></span>
            </div>
          </th>
          <th className="col-xs-3 col-sm-4 col-md-3" scope="col">
            <div>
              <label htmlFor="inputPosition" className="control-label">Position</label>
            </div>
            <div className="input-group">
              <select className="form-control" id="inputPosition" ref="positionFilter" onChange={this.handleFilterPositionChange}>
                <option value="">All Positions</option>
                <option value="Goalkeeper">Goalkeeper</option>
                <option value="Defender">Defender</option>
                <option value="Midfielder">Midfielder</option>
                <option value="Forward">Forward</option>
              </select>
              <span className="input-group-addon sort"><button ref="positionSort" onClick={this.handleSort} value="Position" data-direction="ascending">Sort</button></span>
            </div>
          </th>
          <th className="hidden-xs hidden-sm col-md-2" scope="col">
            <div><label htmlFor="citizenshipSort" className="control-label">Citizenship</label></div>
            <div>
              <button className="btn btn-default hidden-xs hidden-sm col-md-12" ref="citizenshipSort" onClick={this.handleSort} value="Citizenship" data-direction="ascending">Sort</button>
            </div>
          </th>
          <th className="col-xs-2"  scope="col">
            <div>
              <label htmlFor="inputBirthplace" className="control-label">Birthplace</label>
            </div>
            <input className="form-control" id="inputBirthplace" type="text" ref="birthplaceFilter" onChange={this.handleFilterBirthplaceChange} placeholder="Birthplace" />
          </th>
          <th className="col-xs-1" scope="col">
            Height<br />
            <button className="btn btn-default" ref="heightSort" onClick={this.handleSort} value="Height_Total" data-direction="ascending">Sort</button>
          </th>
          <th className="col-xs-1" scope="col">
            Weight<br />
            <button className="btn btn-default" ref="weightSort" onClick={this.handleSort} value="Weight" data-direction="ascending">Sort</button>
          </th>
        </tr>
      </thead>
    );
  }
});

var DisplayBody = React.createClass({
  render: function() {
    var playerRows = this.props.data.map(function (players) {
      return (
        <DisplayRow data={players} />
      );
    });
    return (
      <tbody className="DisplayBody">
        {playerRows}
      </tbody>
    );
  }
});

var DisplayRow = React.createClass({
  render: function() {
    var name = this.props.data.PlayerName;
    var position = this.props.data.Position;
    var citizenship = this.props.data.Citizenship;
    var birthplace = this.props.data.Birthplace;
    var height = this.props.data.Height_Feet + "' " + this.props.data.Height_Inches + '"';
    var weight = this.props.data.Weight;
    return (
      <tr>
        <td><strong>{name}</strong></td>
        <td>{position}</td>
        <td className="hidden-xs hidden-sm visible-md-block visible-lg-block">{citizenship}</td>
        <td>{birthplace}</td>
        <td>{height}</td>
        <td>{weight}</td>
      </tr>
    );
  }
});

React.render(
  <PlayerDisplay url="/api/players-crew.json" />,
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