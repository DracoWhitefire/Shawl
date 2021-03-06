<?php

/**
 * Represents the schedule of a user $userId for $weekday
 */
Class Model_Schedule extends Model_Dao
{
    public $id;
    public $presetId;
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
    protected static $_tableName = "schedules";
    
    public function __construct() {
        $this->_timeFormat = "G:i";
        $this->_intFormat = "%h:%I";
        $this->_unpaidBreakInterval = 30;
        $this->_minimumBeforeUnpaid = 4*60;
    }
    
    /**
     * Returns a Schedule object for $presetId and $weekday;
     * @global db object $db
     * @param int $userId
     * @param string $weekday
     * @return object - instance of Schedule
     */
    public static function getByPresetByDay($presetId, $weekday) {
        $query  = "SELECT * ";
        $query .= "FROM `schedules` ";
        $query .= "WHERE `preset_id` = " . (int) $presetId . " ";
        $query .= "AND `weekday_id` = " . (int) $weekday . " ";
        $query .= "ORDER BY `id` ASC ";
        $query .= "LIMIT 1";
        $object = self::getByQuery($query);
        return $object;
    }
    
    /**
     * Converts an amount of minutes to a string for constructing new DateInterval
     * @param integer $minutes
     * @return string $breakIntString - String for constructing new DateInterval
     */
    protected function _minutesToIntervalstring($minutes) {
        $breakIntString  = "PT";
        $breakIntString .= intval($minutes / 60) . "H";
        $breakIntString .= $minutes % 60 . "M";
        return (string) $breakIntString;
    }
    
    /**
     * Returns and sets a H:MM formatted string of scheduled hours
     * @return string - formatted string
     */
    public function getScheduledHours() {
        if($this->_scheduledHours == NULL) {
            $breakInt = new DateInterval($this->_minutesToIntervalstring($this->_unpaidBreakInterval));
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
     * Returns start time of schedule;
     * @return string - Start time formatted as H:MM
     */
    public function getStarttime() {
        if(!isset($this->_startTimeObject) || is_null($this->_startTimeObject)) {
            $this->_startTimeObject = new DateTime($this->_startTime);
        }
        return $this->_startTimeObject->format($this->_timeFormat);
    }
    
    /**
     * Returns end time of schedule;
     * @return string - End time formatted as H:MM
     */
    public function getEndtime() {
        if(!isset($this->_endTimeObject) || is_null($this->_endTimeObject)) {
            $this->_endTimeObject = new DateTime($this->_endTime);
        }
        return $this->_endTimeObject->format($this->_timeFormat);
    }
    
    /**
     * Sets the start time of a schedule;
     * @param string $time - The time to be set
     */
    public function setStarttime($time) {
        $this->_starTimeObject = new DateTime($time);
        $this->_startTime = $time;
    }
    
    /**
     * Sets the end time of a schedule;
     * @param string $time - The time to be set
     */
    public function setEndtime($time) {
        $this->_endTimeObject = new DateTime($time);
        $this->_endTime = $time;
    }
}