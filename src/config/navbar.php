<?php
class getNavbar
{
    private $currentPage;
    private $home = '<a href="https://trains.candycryst.com/">Homepage</a>';
    private $brand = '<a>CandyCryst | Trains</a>';
    private $stationList = '<a href="https://trains.candycryst.com/stationList.php">Station List</a>';
    private $trainList = '<a href="https://trains.candycryst.com/trainList.php">Train list</a>';
    private $trainListLog = '<a href="https://trains.candycryst.com/trainLog.php">Train list log</a>';
    private $stationMap = '<a href="https://trains.candycryst.com/stationMap.php">Station Map</a>';


    function __construct($loggedIn, $isAdmin, $currentPage)
    {
        $this->loggedIn = $loggedIn;
        $this->isAdmin = $isAdmin;
        $this->currentPage = $currentPage;
    }

    function setCurrentPage()
    {
        switch ($this->currentPage) {
            case "home":
                $this->home = str_replace("a href", "a class='active' href", $this->home);
                break;
            case "stationList":
                $this->stationList = str_replace("a href", "a class='active' href", $this->stationList);
                break;
            case "trainList":
                $this->trainList = str_replace("a href", "a class='active' href", $this->trainList);
                break;
            case "trainListLog":
                $this->trainListLog = str_replace("a href", "a class='active' href", $this->trainListLog);
                break;
            case "stationMap":
                $this->stationMap = str_replace("a href", "a class='active' href", $this->stationMap);
                break;
        }
    }

    function getNavbar()
    {
        $this->setCurrentPage();
        $navbar = '<div class="topnav" id="topnav">' . $this->brand . $this->home . $this->stationList . $this->trainList .  $this->stationMap;
        // if ($this->loggedIn == 1) {
        // $navbar .= $this->profile . $this->logout . $this->list;
        // } else {
        //     $navbar .= $this->login . $this->signup;
        // }
        // if ($this->isAdmin == 1) {
        //     $navbar .= $this->admin . $this->adminmap;
        // }
        $navbar .= '<a href="javascript:void(0);" class="icon" onclick="topnav()"><i class="fa fa-bars"></i></a></div>';
        return $navbar;
    }
}
