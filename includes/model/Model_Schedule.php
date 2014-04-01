<?php

/**
 * Represents the schedule of a user $userId for $weekday
 */
Class Model_Schedule extends Model_Dao
{
    public $id;
    public $userId;
    public $weekdayId;
    protected $_startTime;
    protected $_endTime;
    protected $_starTimeObject;
    protected $_endTimeObject;
    protected $_timeFormat;
    protected $_intFormat;
    protected $_unpaidBreakInterval;
    protected $_minimumBeforeUnpaid;
    protected $_scheduledHours;
    protected $_tableName = "schedules";
    
    public function __construct() {
        $this->_timeFormat = "G:i";
        $this->_intFormat = "%h:%I";
        $this->_unpaidBreakInterval = 30;
        $this->_minimumBeforeUnpaid = 4*60;
    }
    
    /**
     * get_by_user_day
     * Returns a Schedule object for $userId and $weekday;
     * @global db object $db
     * @param int $userId
     * @param string $weekday
     * @return object - instance of Schedule
     */
    public static function get_by_user_day($userId, $weekday) {
        global $db;
        $query  = "SELECT * ";
        $query .= "FROM `schedules` ";
        $query .= "WHERE `user_id` = " . (int) $userId . " ";
        $query .= "AND `weekday_id` = " . (int) $weekday . " ";
        $query .= "ORDER BY `id` ASC ";
        $query .= "LIMIT 1";
        $object = self::get_by_query($query);
        return $object;
    }
    
    /**
     * _minutes_to_intervalstring
     * Converts an amount of minutes to a string for constructing new DateInterval
     * @param integer $minutes
     * @return string $breakIntString - String for constructing new DateInterval
     */
    protected function _minutes_to_intervalstring($minutes) {
        $breakIntString  = "PT";
        $breakIntString .= intval($this->_unpaidBreakInterval / 60) . "H";
        $breakIntString .= $this->_unpaidBreakInterval % 60 . "M";
        return (string) $breakIntString;
    }
    
    /**
     * get_scheduledHours
     * Returns and sets a H:MM formatted string of scheduled hours
     * @return string - formatted string
     */
    public function get_scheduledhours() {
        if($this->_scheduledHours == NULL) {
            $breakInt = new DateInterval($this->_minutes_to_intervalstring($this->_unpaidBreakInterval));
            $workingInterval = $this->_startTimeObject->diff($this->_endTimeObject);
            $intervalMinutes = $workingInterval->m + ($workingInterval->h * 60);
            if($intervalMinutes <= $this->_minimumBeforeUnpaid) {
                $this->_scheduledHours = $workingInterval->format($this->_intFormat);
            } else {
                $realInterval = $this->_startTimeObject->diff($this->_endTimeObject->sub($breakInt));
                $this->_scheduledHours = $realInterval->format($this->_intFormat);
            }
        }
        return $this->_scheduledHours;
    }
    
    /**
     * get_starttime()
     * Returns start time of schedule;
     * @return string - Start time formatted as H:MM
     */
    public function get_starttime() {
        if(!isset($this->_startTimeObject) || is_null($this->_startTimeObject)) {
            $this->_startTimeObject = new DateTime($this->_startTime);
        }
        return $this->_startTimeObject->format($this->_timeFormat);
    }
    
    /**
     * get_endtime()
     * Returns end time of schedule;
     * @return string - End time formatted as H:MM
     */
    public function get_endtime() {
        if(!isset($this->_endTimeObject) || is_null($this->_endTimeObject)) {
            $this->_endTimeObject = new DateTime($this->_endTime);
        }
        return $this->_endTimeObject->format($this->_timeFormat);
    }
    
    public function set_starttime($time) {
        $this->_starTimeObject = new DateTime($time);
        $this->_startTime = $time;
    }
    
    public function set_endtime($time) {
        $this->_endTimeObject = new DateTime($time);
        $this->_endTime = $time;
    }
}