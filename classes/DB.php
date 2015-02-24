<?php
// PHP OOP Login/Register System: Database Querying (Part 8/23)
class DB {
    private static $_instance = null;
    private $_pdo,
            $_query,
            $_error = false,
            $_results,
            $_count = 0;
    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch(PDOException $e) {
            die($e->getMessage());
        }
    }
    public static function getInstance() {
        if(!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    // PHP OOP Login/Register System: Database Querying (Part 8/23)
    public function query($sql, $params = array()) {
        // echo 'starting query function', '<br>';
        $this->_error = false;
        // assign $_pdo prepared sql to $_query;
        if($this->_query = $this->_pdo->prepare($sql)) {
            // echo '_pdo prepare function is being executed','<br>';

            $x = 1;
            if(count($params)) {
                // if params were supplied iterate over each one and bind its value to $x
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }

            if($this->_query->execute()) {
                // store query object in $_results;
                // echo 'execute function passed';
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();

            } else {
                print_r($this->_query->errorInfo());

                // print_r($this->_query->);

                $this->_error = true;
            }
        }
        return $this;
    }
    public function action($action, $table, $where = array()) {
        //check for field, operator and value = 3
        if(count($where) === 3 ) {
            // set allowed operators
            $operators = array('=', '>', '<', '>=', '<=');

            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];

            if( in_array($operator, $operators) ) {
                // $sql = "SELECT * FROM users WHERE username = 'marcin'";
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        return false;
    }
    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }
    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }
    public function insert($table, $fields = array()) {
        if( count($fields) ) {
            // pass $fields to array_keys to get just the keys
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach( $fields as $field ) {
                $values .= '?';
                if ( $x < count($fields) ) {
                    $values .= ', ';
                }
                $x++;
            }

            // this is how a standard query would look like $sql = "INSERT INTO users (username, password, salt)";
            // complete query
            //INSERT INTO `lr`.`users` (`id`, `username`, `password`, `salt`, `name`, `joined`, `group`) VALUES (NULL, 'grazyna', 'password', 'salt', 'grazyna s', '2015-01-02 00:00:00', '1')
            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES({$values})";
            // $sql = "INSERT INTO {$table}(" . implode(',', $keys) . ") VALUES({$values})";



            if(!$this->query($sql, $fields)->error()) {
                return true;
            } else {
                echo 'Something din\'t allow to build the query' . '<br>';
                echo 'error: ' . $this->error();
            }
        }
        return false;
    }

    public function update($table, $id, $fields) {
        $set = '';
        $x = 1;

        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        // die($set);
        // $sql "UPDATE users SET password = 'newpassword' WHERE id = 2";
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $fields)->error()) {
            return true;
        }
        return false;
    }
    // PHP OOP Login/Register System: Database Results (Part 9/23)
    public function results() {
        return $this->_results;
    }
    public function first() {
        return $this->results()[0];
    }
    public function error() {
        return $this->_error;
    }

    public function count() {
        return $this->_count;
    }
}
