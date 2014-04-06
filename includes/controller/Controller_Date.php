<?php

/**
 * Controller_Date
 */
Class Controller_Date
{
    /**
     * Gets selected date from GET request
     * @return array $getDateArray - array with selected date
     */
    private static function _fromGet() {
        $getDateArray = array();
        if(!isset($_GET["y"])) {
            $getDateArray["y"] = date("Y");
        } else {
            $getDateArray["y"] = urldecode($_GET["y"]);
        }
        if(!isset($_GET["m"])) {
            $getDateArray["m"] = date("m");
        } else {
            if((1 <= $_GET["m"]) && ($_GET["m"] <= 12)) {
                $getDateArray["m"] = urldecode($_GET["m"]);
            } else {
                $getDateArray["m"] = date("m");
            }
        }
        if(isset($_GET["d"])) {
            if((1<=$_GET["d"])&&($_GET["d"]<=cal_days_in_month(CAL_GREGORIAN, $getDateArray["m"], $getDateArray["y"]))) {
                $getDateArray["d"] = urldecode($_GET["d"]);
            } else {
                $getDateArray["d"] = date("d");
            }
        } else {
            $getDateArray["d"] = date("d");				
        }
        return (array) $getDateArray;
    }

    /**
     * Gets selected date from POST request 
     * @return array $postDateArray - array with selected date
     */
    private static function _fromPost() {
        $postDateArray["d"] = $_POST["d"];
        $postDateArray["m"] = $_POST["m"];
        $postDateArray["y"] = $_POST["y"];
        return (array) $postDateArray;
    }
    
    /**
     * Gets selected date from SESSION 
     * @return array $sessionDateArray - array with selected date
     */
    private static function _fromSession() {
        $sessionDateArray = $_SESSION["selectedDate"];
        return (array) $sessionDateArray;
    }
    
    /**
     * Creates a url for GET request anchors
     * @param string $year
     * @param string $month
     * @param string $day
     * @return string $url - URL for GET request
     */
    public static function to_get($year = "", $month = "", $day = "") {
        $urlQueries_array = array();
        $urlQueries_array["y"] =    !empty($year) 
                                    ? urlencode($year) 
                                    :  (isset($urlQueries_array["y"]) 
                                       ? $urlQueries_array["y"]
                                       : date("Y"));
        $urlQueries_array["m"] =    !empty($month) 
                                    ? urlencode($month) 
                                    :  (isset($urlQueries_array["m"]) 
                                       ? $urlQueries_array["m"]
                                       : date("n"));
        $urlQueries_array["d"] =    !empty($day) 
                                    ? urlencode($day) 
                                    :  (isset($urlQueries_array["d"]) 
                                       ? $urlQueries_array["d"]
                                       : date("j"));
        $url = new Controller_Url();
        $url->add($urlQueries_array);
        return (string) $url;
    }
    
    /**
     * Gets selected date
     * @return array $dateArray - array with selected date
     */
    public static function get_selected() {
        $dateArray = array();
        if(isset($_POST["d"]) || isset($_POST["m"]) || isset($_POST["y"])) {
            $dateArray = self::_fromPost();
        } elseif(isset($_SESSION["selectedDate"])) {
            $dateArray = self::_fromSession();
        } elseif(isset($_GET["d"]) || isset($_GET["m"]) || isset($_GET["y"])) {
            $dateArray = self::_fromGet();
        } else {
            $dateArray["y"] = date("Y");
            $dateArray["m"] = date("n");
            $dateArray["d"] = date("j");
        }
        return (array) $dateArray;        
    }
}