<?

class database {

    private $host = 'localhost';
    private $login = 'trixec_rtd';
    private $password = 'xYCPc&7N';
    private $db = 'trixec_rtd';
    private $sender;
    private $link;
    private $insert_id;
    private $num_rows;
    private $query;
    private $query_count = 0;
    private static $instance = null;

    public static function get_instance() {
        if (self::$instance == null) {
            self::$instance = new database();
        }

        return self::$instance;
    }

    private function __construct($host = null, $login = null, $password = null, $db = null) {
        $this->login = ($login != null) ? $login : $this->login;
        $this->password = ($password != null) ? $password : $this->password;
        $this->host = ($host != null) ? $host : $this->host;
        $this->db = ($db != null) ? $db : $this->db;

        $this->link = mysqli_connect($this->host, $this->login, $this->password, $this->db) /*or die('Construct class \'database\' error. Connect error ' . mysqli_connect_errno() . ': ' . mysqli_connect_error())*/;
        mysqli_set_charset($this->link, 'utf8');
    }

    public function __destruct() {
        mysqli_close($this->link);
    }

    public function num_rows() {
        return $this->num_rows;
    }

    public function insert_id() {
        return $this->insert_id;
    }

    public function query_count() {
        return $this->query_count;
    }

    public function query_text() {
        return $this->query;
    }

    public function query($q) {
        $temp = mysqli_query($this->link, $q);

        if (!is_object($temp)) {
            $this->insert_id = mysqli_insert_id($this->link);
        } else {
            $this->sender = $temp;
            $this->num_rows = $temp->num_rows;
        }

        if ($this->link->errno) {
            /*die('Database error ' . $this->link->errno . ': ' . $this->link->error . '<br>Query text: ' . $q . '<br>');*/
        }

        $this->query_count++;

        return $this->sender;
    }

    public function select($table) {
        $this->query = "SELECT * FROM `" . $table . "`";

        return $this;
    }

    public function insert($table, $param, $value) {
        $params = explode(',', $param);
        $values = explode(',', $value);
        $temp = '';

        for ($i = 0; $i < sizeof($params); $i++) {
            $temp .= "`" . $this->escape($params[$i]) . "` = '" . $this->escape($values[$i]) . "', ";
        }

        $temp = substr($temp, 0, -2);

        $this->query = "INSERT INTO `" . $this->escape($table) . "` SET " . $temp;

        return $this;
    }

    public function update($table, $param, $value) {
        $params = explode(',', $param);
        $values = explode(',', $value);

        for ($i = 0; $i < sizeof($params); $i++) {
            $temp .= "`" . $this->escape($params[$i]) . "` = '" . $this->escape($values[$i]) . "', ";
        }

        $temp = substr($temp, 0, -2);

        $this->query = "UPDATE `" . $this->escape($table) . "` SET " . $temp;

        return $this;
    }

    public function delete($table) {
        $this->query = "DELETE FROM `" . $this->escape($table) . "`";

        return $this;
    }

    public function where($param, $value, $operand = '=') {
        if ($operand == '=' or $operand == '>' or $operand == '<' or $operand == '>=' or $operand == '<=') {
            $this->query .= " WHERE `" . $this->escape($param) . "` " . $operand . " '" . $this->escape($value) . "'";
        } else {
            if ($operand == 'LIKE_%') {
                $this->query .= " WHERE `" . $this->escape($param) . "` " . 'LIKE' . " '" . $this->escape($value) . "%'";
            }
            if ($operand == 'LIKE%%') {
                $this->query .= " WHERE `" . $this->escape($param) . "` " . 'LIKE' . " '%" . $this->escape($value) . "%'";
            }
            if ($operand == 'LIKE%_') {
                $this->query .= " WHERE `" . $this->escape($param) . "` " . 'LIKE' . " '%" . $this->escape($value) . "'";
            }
            if ($operand == 'LIKE') {
                $this->query .= " WHERE `" . $this->escape($param) . "` " . 'LIKE' . " '" . $this->escape($value) . "'";
            }
        }    
        
        return $this;
    }

    public function order($by, $asc = true) {
        $this->query .= " ORDER BY `" . $this->escape($by) . "`";

        if (!$asc) {
            $this->query .= " DESC";
        }

        return $this;
    }

    public function _and($param, $value, $operand = '=') {
        if ($operand == '=' or $operand == '>' or $operand == '<' or $operand == '>=' or $operand == '<=') {
            $this->query .= " AND `" . $this->escape($param) . "` " . $operand . " '" . $this->escape($value) . "'";
        } else {
            if ($operand == 'LIKE_%') {
                $this->query .= " AND `" . $this->escape($param) . "` " . 'LIKE' . " '" . $this->escape($value) . "%'";
            }
            if ($operand == 'LIKE%%') {
                $this->query .= " AND `" . $this->escape($param) . "` " . 'LIKE' . " '%" . $this->escape($value) . "%'";
            }
            if ($operand == 'LIKE%_') {
                $this->query .= " AND `" . $this->escape($param) . "` " . 'LIKE' . " '%" . $this->escape($value) . "'";
            }
            if ($operand == 'LIKE') {
                $this->query .= " AND `" . $this->escape($param) . "` " . 'LIKE' . " '" . $this->escape($value) . "'";
            }
        }        

        return $this;
    }

    public function _or($param, $value, $operand = '=') {
        if ($operand == '=' or $operand == '>' or $operand == '<' or $operand == '>=' or $operand == '<=') {
            $this->query .= " OR `" . $this->escape($param) . "` " . $operand . " '" . $this->escape($value) . "'";
        } else {
            if ($operand == 'LIKE_%') {
                $this->query .= " OR `" . $this->escape($param) . "` " . 'LIKE' . " '" . $this->escape($value) . "%'";
            }
            if ($operand == 'LIKE%%') {
                $this->query .= " OR `" . $this->escape($param) . "` " . 'LIKE' . " '%" . $this->escape($value) . "%'";
            }
            if ($operand == 'LIKE%_') {
                $this->query .= " OR `" . $this->escape($param) . "` " . 'LIKE' . " '%" . $this->escape($value) . "'";
            }
            if ($operand == 'LIKE') {
                $this->query .= " OR `" . $this->escape($param) . "` " . 'LIKE' . " '" . $this->escape($value) . "'";
            }
        }        

        return $this;
    }

    public function limit($limit) {
        $this->query .= " LIMIT " . $this->escape($limit) . "";

        return $this;
    }

    public function get() {
        $temp = $this->query($this->query);

        if (!is_object($temp)) {
            $result = $temp;
        } else {
            $result = $this->get_data($temp);
        }

        return $result;
    }

    public function apply() {
        return $this->query($this->query);
    }
    
    public function qqq() {
        return $this->query;
    }

    public function get_row($param = false) {
        if ($param) {
            $temp = $param;
        } else {
            $temp = $this->sender;
        }

        if (@is_object($temp)) {
            return mysqli_fetch_object($temp);
        } else {
            /*die('Function get_row() error: param \'' . $param . '\' is ' . gettype($param) . ', must be object');*/
        }
    }

    public function get_data() {
        $data = [];

        for ($i = 0; $i < $this->num_rows; $i++) {
            $data[$i] = mysqli_fetch_object($this->sender);
        }

        return $data;
    }

    public function escape($param, $tags = false, $html = false) {
        if (!$tags) {
            $param = strip_tags($param);
        }

		if (!$html) {
			$param = htmlspecialchars($param);
		}
		
        $param = trim($param);
        $param = mysqli_real_escape_string($this->link, $param);

        return $param;
    }

}

function db() {
    return database::get_instance();
}