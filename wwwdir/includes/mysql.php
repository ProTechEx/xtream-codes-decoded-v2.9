<?php

class ipTV_db
{
    public $result;
    var $last_query;
    protected $dbuser;
    protected $dbpassword;
    protected $dbname;
    protected $dbhost;
    public $dbh;
    protected $pconnect = false;
    protected $connected = false;
    function __construct($db_user, $db_pass, $db_name, $host, $db_port = 7999, $pconnect = false, $mysql_config_status = false)
    {
        $this->dbh = false;
        if (!$mysql_config_status) {
            $this->dbuser = $db_user;
            $this->dbpassword = $db_pass;
            $this->dbname = $db_name;
            $this->dbhost = $host;
            $this->pconnect = $pconnect;
            $this->dbport = $db_port;
        }
    }
    function close_mysql()
    {
        if ($this->connected && !$this->pconnect) {
            $this->connected = false;
            $this->dbh->close();
        }
        return true;
    }
    function __destruct()
    {
        $this->close_mysql();
    }
    function db_connect()
    {
        if ($this->connected && $this->dbh) {
            return true;
        }
        $this->dbh = mysqli_init();
        $this->dbh->options(MYSQLI_OPT_CONNECT_TIMEOUT, 4);
        if ($this->pconnect) {
            $this->dbh->real_connect('p:' . $this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname, $this->dbport);
        } else {
            $this->dbh->real_connect($this->dbhost, $this->dbuser, $this->dbpassword, $this->dbname, $this->dbport);
        }
        if (!empty($this->dbh->error)) {
            die(json_encode(array('error' => 'MySQL: ' . $this->dbh->error)));
        }
        $this->connected = true;
        mysqli_set_charset($this->dbh, 'utf8');
        return true;
    }
    function query($query, $buffered = false)
    {
        $this->db_connect();
        $numargs = func_num_args();
        $arg_list = func_get_args();
        $list = array();
        $index = 1;
        while ($index < $numargs) {
            $list[] = mysqli_real_escape_string($this->dbh, $arg_list[$index]);
            $index++;
        }
        $query = vsprintf($query, $list);
        $this->last_query = $query;
        if ($buffered === true) {
            $this->result = mysqli_query($this->dbh, $query, MYSQLI_USE_RESULT);
        } else {
            $this->result = mysqli_query($this->dbh, $query);
        }
        if (!$this->result) {
            ipTV_lib::SaveLog('MySQL Query Failed [' . $query . ']: ' . mysqli_error($this->dbh));
            return false;
        }
        return true;
    }
    function get_rows($use_id = false, $column_as_id = '', $unique_row = true, $column = '')
    {
        if ($this->dbh && $this->result) {
            $rows = array();
            if ($this->num_rows() > 0) {
                while ($row = mysqli_fetch_array($this->result, MYSQLI_ASSOC)) {
                    if ($use_id && array_key_exists($column_as_id, $row)) {
                        if (!isset($rows[$row[$column_as_id]])) {
                            $rows[$row[$column_as_id]] = array();
                        }
                        if (!$unique_row) {
                            if (!empty($column) && array_key_exists($column, $row)) {
                                $rows[$row[$column_as_id]][$row[$column]] = $row;
                            } else {
                                $rows[$row[$column_as_id]][] = $row;
                            }
                        } else {
                            $rows[$row[$column_as_id]] = $row;
                        }
                    } else {
                        $rows[] = $row;
                    }
                }
            }
            mysqli_free_result($this->result);
            return $rows;
        }
        return false;
    }
    public function get_row()
    {
        if ($this->dbh && $this->result) {
            $row = array();
            if ($this->num_rows() > 0) {
                $row = mysqli_fetch_array($this->result, MYSQLI_ASSOC);
            }
            mysqli_free_result($this->result);
            return $row;
        }
        return false;
    }
    public function get_col()
    {
        if ($this->dbh && $this->result) {
            $row = false;
            if ($this->num_rows() > 0) {
                $row = mysqli_fetch_array($this->result, MYSQLI_NUM);
                $row = $row[0];
            }
            mysqli_free_result($this->result);
            return $row;
        }
        return false;
    }
    public function affected_rows()
    {
        $mysqli_affected_rows = mysqli_affected_rows($this->dbh);
        return empty($mysqli_affected_rows) ? 0 : $mysqli_affected_rows;
    }
    public function simple_query($query)
    {
        $this->db_connect();
        $this->result = mysqli_query($this->dbh, $query);
        if (!$this->result) {
            ipTV_lib::SaveLog('MySQL Query Failed [' . $query . ']: ' . mysqli_error($this->dbh));
            return false;
        }
        return true;
    }
    public function escape($string)
    {
        $this->db_connect();
        return mysqli_real_escape_string($this->dbh, $string);
    }
    public function num_fields()
    {
        $mysqli_num_fields = mysqli_num_fields($this->result);
        return empty($mysqli_num_fields) ? 0 : $mysqli_num_fields;
    }
    public function last_insert_id()
    {
        $mysql_insert_id = mysqli_insert_id($this->dbh);
        return empty($mysql_insert_id) ? 0 : $mysql_insert_id;
    }
    public function num_rows()
    {
        $mysqli_num_rows = mysqli_num_rows($this->result);
        return empty($mysqli_num_rows) ? 0 : $mysqli_num_rows;
    }
}
?>
