<<<<<<< Updated upstream
<?php
class Connection {
    private $conn;

    public function __construct($username, $password) {
        // define connection
        define("DB_HOST", "localhost");
        define("DB_USERNAME", $username);
        define("DB_PASSWORD", $password);
        define("DB_NAME", "senprojtest");

        // attempt to establish connection
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // check connection is good
        if ($this->conn->connect_error) {
            $exceptionMessage = "Connection error: " .
                $this->conn->connect_error;
            throw new Exception($exceptionMessage);
        }

        // change default settings of connection
        $this->conn->autocommit(FALSE);
        $this->conn->options(MYSQLI_OPT_LOCAL_INFILE, true);
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function get_conn() {
        return ($this->conn);
    }
}
?>
=======
<?php
class Connection {
    private $conn;

    public function __construct($username, $password) {
        // define connection
        define("DB_HOST", "localhost");
        define("DB_USERNAME", $username);
        define("DB_PASSWORD", $password);
        define("DB_NAME", "senprojtest");

        // attempt to establish connection
        $this->conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // check connection is good
        if ($this->conn->connect_error) {
            $exceptionMessage = "Connection error: " .
                $this->conn->connect_error;
            throw new Exception($exceptionMessage);
        }

        // change default settings of connection
        $this->conn->autocommit(FALSE);
        $this->conn->options(MYSQLI_OPT_LOCAL_INFILE, true);
    }

    public function __destruct() {
        $this->conn->close();
    }

    public function get_conn() {
        return ($this->conn);
    }
}
?>
>>>>>>> Stashed changes
