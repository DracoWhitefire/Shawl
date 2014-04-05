<?php

/**
 * Module object for each application module
 */
Class Model_Module extends Model_Dao
{
    public $id;
    public $minRank;
    public $visible;
    public $menuName;
    public $rank;
    public $position;
    private static $_count;
    protected $_tableName = "modules";
        
    /**
     * _getAll
     * Gets all modules
     * @return array|object - array of Model_Module (or single instance of Model_Module)
     */
    private static function _getAll() {
        $query  = "SELECT * ";
        $query .= "FROM `modules` ";
        $query .= "WHERE `visible` = 1 ";
        $query .= "ORDER BY `position` ASC ";
        return self::get_by_query($query);
    }
    
    /**
     * _getById
     * Returns module object by id;
     * @global Controller_Session $session
     * @param int $id - the id if the module
     * @return Model_Module|boolean - Instance of Model_Module or false
     */
    private static function _getById($id) {
        global $session;
        $db = call_user_func(DB_CLASS . "::getInstance");
        if(!((1 <= $id) && ($id <= self::_getCount()))) { // Prevents GET request abuse - will always connect to valid module
            $id = 1;
        }
        $query  = "SELECT * ";
        $query .= "FROM `modules` ";
        $query .= "WHERE `id` = '" . $db->query_prep($id) . "' ";
        $query .= "LIMIT 1";
        $object = self::get_by_query($query);
        if($object->minRank <= $session->rank) {
            return $object;
        } else {
            return FALSE;
        }
    }
    
    /**
     * _getCount
     * Returns the amount of modules;
     * @return int self::$_count - The amount of modules
     */
    private static function _getCount() {
        if(!isset(self::$_count)) {
            $db = call_user_func(DB_CLASS . "::getInstance");
            $query  = "SELECT * ";
            $query .= "FROM `modules` ";
            $set = $db->query($query);
            self::$_count = $db->num_rows($set);
            mysqli_free_result($set);
        }
        return self::$_count;
    }
    
    /**
     * get
     * Gets Subject object(s) from query
     * @global Db Object $db
     * @global Session Object $session - current user session
     * @param $selection - defines the query selection
     * @return object or array of objects
     */
    public static function get($selection = "all") {
        global $session;
        if($selection == "all") {
            return self::_getAll();
        } elseif(is_numeric($selection)) {
            if(isset($session->rank)) {
                return self::_getById($selection);
            } else {
                return self::_getById(LOGIN_MODULE_ID);
            }
        }
    }
    
    /**
     * get_current_id
     * Returns id of current subject
     * @return int - id of current subject
     */
    public static function get_current_id() {
        if(isset($_GET["id"])) {
                (integer) $currentId = $_GET["id"];
        } else {
                (integer) $currentId = 1;
        }
        return $currentId;
    }
}