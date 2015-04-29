<?php
// Based on example at http://coreymaynard.com/blog/creating-a-restful-api-with-php/

require_once 'api.class.php';
class MyAPI extends API
{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

        /*

        // Abstracted out for example
        // $APIKey = new Models\APIKey();
        // $User = new Models\User();
        // echo 'origin: '.$origin;

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
            throw new Exception('Invalid API Key');
        } else if (array_key_exists('token', $this->request) && !$User->get('token', $this->request['token'])) {
            throw new Exception('Invalid User Token');
        }

        $this->User = $User;
        */
    }

    protected function _gamesWhere($verb,$args) {
        $where = "";
        if (!$verb && count($args) == 0) {
            // No verb, no argument - return raw list of records
            $where = "WHERE t.Official = 1 ";
        } elseif (!$verb && count($args) == 1) {
            // No verb, one argument - return single requested record
            $where = "WHERE g.ID = " . $args[0] . " ";
        } elseif ($verb) {
            // Verb supplied - configure query accordingly
            switch ($verb) {
                case "competition":
                    $where = "WHERE g.MatchTypeID = " . $args[0] . " ";
                    break;
                case "date":
                    if (count($args) == 0) {
                        $tempDate = strtotime("now");
                    } else {
                        $tempDate = strtotime($args[0]);
                    }
                    $where = "WHERE DATE_FORMAT(g.MatchTime,\"%c-%e\") = '" . date('n-j',$tempDate) . "' ";
                    break;
                case "team":
                    $where = "WHERE (g.HTeamID = " . $args[0] . " OR g.ATeamID = " . $args[0] . ") ";
                    break;
                case "venue":
                    $where = "WHERE g.VenueID = " . $args[0] . " ";
                    break;
                case "year":
                    $where = "WHERE YEAR(MatchTime) = " . $args[0] . " ";
                    break;
                default:
                    throw new Exception('Unknown game verb');
            }

        } else {
            // Not sure what's happening...
            throw new Exception('Unanticipated request for endpoint');
        }

        $where .= " AND t.Official = 1 ";
        return $where;
    }

    protected function _playersWhere($verb,$args) {
        $where = "";
        if ($verb) {
            // Need a switch statement here for different verbs
            switch ($verb) {
                case "birthdate":
                    if (count($args) == 0) {
                        $tempDate = strtotime("now");
                    } else {
                        $tempDate = strtotime($args[0]);
                    }
                    return "WHERE Month(DOB) = " . date('n',$tempDate) . " AND DAY(DOB) = " . date('j',$tempDate) . " "; 
                case "birthyear":
                    return "WHERE Year(DOB) = " . $args[0] . " ";
                case "country":
                    return "WHERE p.Citizenship = '" . $args[0] . "' ";
                case "search":
                    return "WHERE CONCAT(FirstName,' ',LastName) LIKE '%" . $args[0] . "%' ";
                case "team":
                    return "WHERE p.Current_Club = " . $args[0] . " ";
                default:
                    throw new Exception('Unknown Verb');
            }
        } elseif (!$verb && count($args) == 0) {
            // No qualifiers, just return blank where clause
            return $where;
        } elseif (!$verb && count($args) == 1) {
            if (is_numeric($args[0])) {
                return "WHERE p.ID = " . $args[0] . " ";
            } else {
                return "";
            }
        } else {
            throw new Exception('Unanticipated request for endpoint');
        }
        return $where;
    }

    protected function _teamsWhere($verb,$args) {
        $where = "";
        if (!$verb && count($args) == 0) {
            // No verb, no argument - return raw list of records
            $where = "WHERE ";
        } elseif (!$verb && count($args) == 1) {
            // No verb, one argument - return single requested record
            $where = "WHERE t.ID = " . $args[0] . " AND ";
        } elseif ($verb) {
            // Verb supplied - configure query accordingly
            switch ($verb) {
                case "club":
                    $where = "WHERE t.ClubID = " . $args[0] . " AND ";
                    break;
                case "competition":
                    $where = "WHERE g.MatchTypeID = " . $args[0] . " AND ";
                    if (count($args) == 2) {
                        $where .= "YEAR(MatchTime) = " . $args[1] . " AND ";
                    }
                    break;
                case "league":
                    $where = "WHERE t.League = '". $args[0] ."' AND ";
                    break;
                case "opponent":
                    $where = "WHERE (g.HTeamID = " . $args[0] . " OR g.ATeamID = " . $args[0] . ") AND ";
                    break;
                case "venue":
                    $where = "WHERE t.VenueID = " . $args[0] . " AND ";
                    break;
                default:
                    throw new Exception('Unknown game verb');
            }

        } else {
            // Not sure what's happening...
            throw new Exception('Unanticipated request for endpoint');
        }
        $where .= "m.Official = 1 AND g.MatchTime < now() ";
        return $where;
    }

    /**
     * Map function to augment a player record with the seasons that player appeared
     * 
     * TODO: Parameterize this query
     */
    protected function _playersSeasons($playerID) {

        $sql = "SELECT DISTINCT YEAR(MatchTime) AS Seasons "
        ."FROM tbl_games g "
        ."INNER JOIN tbl_gameminutes m ON g.ID = m.GameID "
        ."WHERE m.PlayerID = " . $playerID . " "
        ."ORDER BY YEAR(MatchTime) ASC";

        return $this->_query($sql,"");
    }

    /**
     * Execute parameterized query from any endpoint
     */
    protected function _query($sql,$args) {
        $recordset = array();

        $rs = mysqli_query($this->connection, $sql) or die(mysqli_error($this->connection));

        while($row = mysqli_fetch_assoc($rs)) {
            $recordset[] = $row;
        }

        return $recordset;
    }

    protected function debug() {
        print_r($this);
    }

    /**
     * List of dates
     */
    protected function dates() {

        if ($this->method != 'GET') {
            return "Only accepts GET requests";
        }

        $games = array();

        $this->args = Array();

        $this->verb = "date";
        $games = Array("games",$this->games());

        $this->verb = "birthdate";
        $players = Array("players",$this->players());

        $return = array_merge($games,$players);

        return $return;
    }

    /**
     * List of games
     */
    protected function games() {

        if ($this->method != 'GET') {
            return "Only accepts GET requests";
        }

        $where = $this->_gamesWhere($this->verb, $this->args);

        $sql = "SELECT g.ID AS ID, MatchTime, HTeamID AS HomeID, h.teamname AS HomeTeam, HScore AS HomeScore, ATeamID AS AwayID, a.teamname AS AwayTeam, AScore AS AwayScore, MatchTypeID, t.MatchType, g.VenueID, v.VenueName, Attendance, MeanTemperature, Notes "
        ."FROM tbl_games g "
        ."INNER JOIN tbl_teams h ON g.HTeamID = h.ID "
        ."INNER JOIN tbl_teams a on g.ATeamID = a.ID "
        ."INNER JOIN lkp_matchtypes t ON g.MatchTypeID = t.ID "
        ."INNER JOIN tbl_venues v on g.VenueID = v.ID "
        .$where
        ."ORDER BY MatchTime ASC";

        return $this->_query($sql,"");
    }

    /**
     * List of players
     */
     protected function players() {
        // $this->debug();

        if ($this->method != 'GET') {
            return "Only accepts GET requests";
        }

        $where = $this->_playersWhere($this->verb, $this->args);

        $sql = "SELECT p.ID AS ID, CONCAT(p.FirstName,' ',p.LastName) AS PlayerName, p.LastName, p.Position, p.RosterNumber, p.Height_Feet, p.Height_Inches, (12*p.Height_Feet)+p.Height_Inches AS Height_Total, p.Weight, p.Birthplace, p.Citizenship, p.DOB AS BirthDate, YEAR(p.DOB) AS BirthYear "
        ."FROM tbl_players p "
        .$where
        ."ORDER BY p.LastName ASC, p.FirstName ASC";

        return $this->_query($sql,"");
    }

    /**
     * List of teams
     */
    protected function teams() {
        if ($this->method != 'GET') {
            return "Only accepts GET requests";
        }

        $where = $this->_teamsWhere($this->verb, $this->args);

        $sql = "SELECT t.ID AS ID, t.teamname AS TeamName, t.League AS League, t.ClubID AS ClubID, t.VenueID AS VenueID, v.VenueName, t.YearFounded, t.YearFolded, t.TeamURL, COUNT(g.ID) AS GamesPlayed "
        ."FROM tbl_teams t "
        ."INNER JOIN tbl_venues v ON t.VenueID = v.ID "
        ."INNER JOIN tbl_games g ON (t.ID = g.HTeamID OR t.ID = g.ATeamID) "
        ."INNER JOIN lkp_matchtypes m ON g.MatchTypeID = m.ID "
        .$where
        ."GROUP BY t.ID "
        ."ORDER BY t.teamname ASC";

        return $this->_query($sql, "");
    }
}

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
