<?php
/**
 * Database Utilities
 * includes/db-helper.php
 */

class Database {
    private $connection;
    private $last_query;
    private $last_error;
    
    public function __construct($host, $user, $password, $database) {
        try {
            $this->connection = new mysqli($host, $user, $password, $database);
            
            if ($this->connection->connect_error) {
                throw new Exception('Database Connection Error: ' . $this->connection->connect_error);
            }
            
            $this->connection->set_charset("utf8mb4");
        } catch (Exception $e) {
            log_message($e->getMessage(), 'error');
            die('Database connection failed');
        }
    }
    
    /**
     * Execute SELECT query
     */
    public function select($table, $conditions = [], $columns = '*', $limit = null) {
        $query = "SELECT $columns FROM `$table`";
        
        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $key => $value) {
                $where[] = "`$key` = '" . $this->connection->real_escape_string($value) . "'";
            }
            $query .= " WHERE " . implode(" AND ", $where);
        }
        
        if ($limit) {
            $query .= " LIMIT $limit";
        }
        
        return $this->execute($query);
    }
    
    /**
     * Execute INSERT query
     */
    public function insert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);
        
        $columns_str = implode("`, `", $columns);
        $values_str = implode("', '", array_map([$this->connection, 'real_escape_string'], $values));
        
        $query = "INSERT INTO `$table` (`$columns_str`) VALUES ('$values_str')";
        
        return $this->execute($query);
    }
    
    /**
     * Execute UPDATE query
     */
    public function update($table, $data, $conditions) {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "`$key` = '" . $this->connection->real_escape_string($value) . "'";
        }
        
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "`$key` = '" . $this->connection->real_escape_string($value) . "'";
        }
        
        $query = "UPDATE `$table` SET " . implode(", ", $set) . " WHERE " . implode(" AND ", $where);
        
        return $this->execute($query);
    }
    
    /**
     * Execute DELETE query
     */
    public function delete($table, $conditions) {
        $where = [];
        foreach ($conditions as $key => $value) {
            $where[] = "`$key` = '" . $this->connection->real_escape_string($value) . "'";
        }
        
        $query = "DELETE FROM `$table` WHERE " . implode(" AND ", $where);
        
        return $this->execute($query);
    }
    
    /**
     * Execute custom query
     */
    public function query($query) {
        return $this->execute($query);
    }
    
    /**
     * Execute query and return results
     */
    private function execute($query) {
        $this->last_query = $query;
        
        try {
            $result = $this->connection->query($query);
            
            if (!$result) {
                throw new Exception('Query Error: ' . $this->connection->error);
            }
            
            if ($result === true) {
                return [
                    'success' => true,
                    'affected_rows' => $this->connection->affected_rows,
                    'insert_id' => $this->connection->insert_id
                ];
            }
            
            // Fetch results
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            
            $result->free();
            
            return [
                'success' => true,
                'rows' => $rows,
                'count' => count($rows)
            ];
        } catch (Exception $e) {
            $this->last_error = $e->getMessage();
            log_message($e->getMessage(), 'error');
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get last query
     */
    public function get_last_query() {
        return $this->last_query;
    }
    
    /**
     * Get last error
     */
    public function get_last_error() {
        return $this->last_error;
    }
    
    /**
     * Count rows
     */
    public function count($table, $conditions = []) {
        $result = $this->select($table, $conditions, 'COUNT(*) as count');
        
        if ($result['success'] && isset($result['rows'][0])) {
            return $result['rows'][0]['count'];
        }
        
        return 0;
    }
    
    /**
     * Get paginated results
     */
    public function paginate($table, $page = 1, $per_page = 10, $conditions = []) {
        $offset = ($page - 1) * $per_page;
        $total = $this->count($table, $conditions);
        
        $data = [];
        foreach ($conditions as $key => $value) {
            $data[] = "`$key` = '" . $this->connection->real_escape_string($value) . "'";
        }
        
        $where = !empty($data) ? "WHERE " . implode(" AND ", $data) : "";
        
        $query = "SELECT * FROM `$table` $where LIMIT $offset, $per_page";
        $result = $this->query($query);
        
        return [
            'success' => $result['success'],
            'data' => $result['rows'] ?? [],
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page,
            'total_pages' => ceil($total / $per_page)
        ];
    }
    
    /**
     * Close connection
     */
    public function close() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
    
    /**
     * Destructor
     */
    public function __destruct() {
        $this->close();
    }
}

// Global database instance
$db = null;

function get_db() {
    global $db;
    
    if ($db === null) {
        $db = new Database(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    }
    
    return $db;
}

?>
