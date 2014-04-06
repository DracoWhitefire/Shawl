<?php
/**
 * Model_User
 */
class Model_User extends Model_Dao
{
    public $id;
    public $userName;
    public $forumName;
    public $firstName;
    public $lastName;
    public $rank;
    public $active;
    public $passwordhash;
    public $team;
    public $employeeNr;
    public $schedulePreset;
    protected static $_tableName = "users";
    
    /**
     * Returns a single User object or array of User objects;
     * @param string $selection - defines the type of get selection
     * @return object|array - single User object or array of User objects
     */
    public static function get($selection = "all") {
        $user_query  = "SELECT * ";
        $user_query .= "FROM `users` ";
        if(is_numeric($selection)) {
            $user_query .= "WHERE `id` = '{$selection}' ";
        } elseif($selection == "active") {
            $user_query .= "WHERE `active` = '1' ";
        } elseif ($selection == "inactive") {
            $user_query .= "WHERE `active` = '0' ";
        }
        $user_query .= "ORDER BY `id` ASC";
        return self::getByQuery($user_query);
    }
    
    /**
     * Returns User object or array of User objects belonging to team $team;
     * @global type $db
     * @param integer $team - the team
     * @return object|array - single User object or array of User objects
     */
    public static function getByTeam($team) {
        $team = call_user_func(DB_CLASS . "::queryPrep", $team);
        $user_query  = "SELECT * ";
        $user_query .= "FROM `users` ";
        $user_query .= "WHERE `team` = '{$team}' ";
        $user_query .= "AND `active` = '1' ";
        $user_query .= "ORDER BY `id` ASC";
        return self::getByQuery($user_query);
    }
    
    /**
     * Returns current User's Model_Schedule for day $day;
     * @param string $day
     * @return object - Model_Schedule for day $day
     */
    public function getSchedule($day) {
        return Model_Schedule::getByPresetByDay($this->schedulePreset, $day);
    }
    
    /**
     * Generates salt for pw_encrypt;
     * @param integer $length - required salt length
     * @return string - the salt
     */
    private static function _generateSalt($length) {
        return substr(str_replace("+", ".", base64_encode(md5(uniqid(mt_rand(), TRUE)))),0, $length);
    }
    
    /**
     * Encrypts password using the salt from _generate_salt();
     * @param string $pw_string - the raw password
     * @return string $hashPw - the hashed password
     */
    public static function pwEncrypt($pw_string) {
        $hashFormat = "$2y$10$";
        $saltLength = 22;
        $hashSalt = self::_generateSalt($saltLength);
        $hashFormatSalt = $hashFormat . $hashSalt;
        $hashPw = crypt(trim($pw_string), $hashFormatSalt);
        return $hashPw;
    }
    
    /**
     * Checks whether password is correct;
     * @param string $pw_string - the raw password
     * @return boolean - is password correct?
     */
    public function pwCheck($pw_string) {
        $hash = crypt($pw_string, $this->passwordhash);
        if($hash === $this->passwordhash) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}