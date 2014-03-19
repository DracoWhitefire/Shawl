<?php

/**
 *  interface for db classes
 *  for technology independence
 */
interface Model_DbInterface
{
    public function disconnect();

    /**
     * query_prep
     * Prepares a query for database operation;
     * @param string $value - raw query
     * @return string $value - safe query
     */
    public function query_prep($value);

    /**
     * fetch_assoc
     * Fetches associative array from query result;
     * @param result $result - result set from db query
     * @return array - associative array from result set
     */
    public function fetch_assoc($result);

    /**
     * num_rows
     * Returns number of rows in result set;
     * @param result $result - result set from db query
     * @return int $count - number of rows in result set
     */
    public function num_rows($result);

    /**
     * insert_id
     * Returns id of last inserted row;
     * @return int - id of last insert
     */
    public function insert_id();

    /**
     * affected_rows
     * Returns amount of rows affected by last query;
     * @return int - amount of affected rows
     */
    public function affected_rows();

    /**
     * query
     * Processes MySQL query
     * @param string $query - The query to be processed
     * @return result $result - Result set from query
     */
    public function query($query);
}
?>