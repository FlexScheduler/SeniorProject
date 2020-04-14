<?php
class Connection
{
    private $conn;

    public function __construct($hostname, $username, $password, $dbname)
    {
        // define connection
        define("DB_HOST", $hostname);
        define("DB_USERNAME", $username);
        define("DB_PASSWORD", $password);
        define("DB_NAME", $dbname);

        // attempt to establish connection
        if ($this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME)) {
            // change default settings of connection
            $this->conn->autocommit(false);
            $this->conn->options(MYSQLI_OPT_LOCAL_INFILE, true);
        }
    }

    public function __destruct()
    {
        $this->conn->close();
    }

    public function get_conn()
    {
        return ($this->conn);
    }
}
