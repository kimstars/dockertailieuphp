<?php

require_once('./core/Database.php');
require_once('./app/Models/Pagination.php');

class Model extends Pagination
{
    protected $dbConnection;

    protected $table;

    //Allow fields in fillable
    protected $fillable = [];

    //Hidden fields
    protected $hidden = [];

    protected $primaryKey = "id";

    protected $data = [];

    public function __construct()
    {
        $this->dbConnection = DatabaseConnection::getInstance();
        $this->checkRequiredProperties();
    }

    /**
     * Check required properties in child Model class.
     *
     * @return void
     */
    public function checkRequiredProperties()
    {
        if (!property_exists($this, 'table')) {
            throw new Exception('Please add property table to class ' . get_class($this));
        }

        if (!property_exists($this, 'fillable')) {
            throw new Exception("Please add property fillable with fields from database to class " . get_class($this));
        }
    }

    /**
     * Begin querying
     *
     * @param array $columns
     * @return array
     */
    public function findAll($columns = ['*'])
    {
        $columns = implode(',', $columns);
        $sql = "SELECT $columns FROM {$this->table} order by {$this->primaryKey} desc";
        $result = $this->dbConnection->query($sql);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    /**
     * GET first record
     *
     * @param [type] $id
     */
    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} where {$this->primaryKey} = {$id}";
        $result = $this->dbConnection->query($sql);
        $data = $result->fetch_assoc();
        return $data;
    }

    //========= XỬ LÍ SQL CHO SẴN ============
    public function getFirst($sql)
    {
        $result = $this->dbConnection->query($sql);
        $data = $result->fetch_assoc();
        if ($data) {
            $data = array_filter($data, function ($key) {
                return !in_array($key, $this->hidden);
            }, ARRAY_FILTER_USE_KEY);
        }
        return $data;
    }

    public function getAll($sql)
    {
        $result = $this->dbConnection->query($sql);
        $data = $result->fetch_all(MYSQLI_ASSOC);
        return $data;
    }

    //============= CRUD ==============

    public function create($data, $check = false)
    {   
        try {
            if (count($data) > 0) {
                $data = array_filter($data, function ($key) {
                    return in_array($key, $this->fillable);
                }, ARRAY_FILTER_USE_KEY);
                $keys = array_keys($data);
                $values = array_map(function ($item) {
                    return "'$item'";
                }, array_values($data));
    
                $fields = implode(',', $keys);
                $values = implode(',', $values);
                $sql = "INSERT INTO {$this->table}({$fields}) VALUES ($values)";
                if ($check) {
                    dd($sql);
                }
                $res = $this->dbConnection->query($sql);
                if ($res) {
                    $pk = $this->primaryKey;
                    return array_merge($data, [$pk => $this->dbConnection->insert_id]);
                }
            }
        } catch(Exception $e) {
            throw $e;
        }
        return false;
    }

    /**
     * Update
     *
     * @param [type] $data
     * @param [type] $id
     * @return void
     */
    public function update($data, $id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = $id";
        $foundRecord = $this->getFirst($sql);
        if (count($foundRecord) != 0) {
            if (count($data) > 0) {
                $data = array_filter($data, function ($key) {
                    return in_array($key, $this->fillable);
                }, ARRAY_FILTER_USE_KEY);

                $updateDataString = implode(',', array_map(function ($key, $value) {
                    return "$key = '$value'";
                }, array_keys($data), array_values($data)));

                $sql = "UPDATE {$this->table} SET $updateDataString WHERE {$this->primaryKey} = $id";
                $result = $this->dbConnection->query($sql);
                if ($result) {
                    $data = array_filter($data, function ($key) {
                        return !in_array($key, $this->hidden);
                    }, ARRAY_FILTER_USE_KEY);
                    return array_merge($foundRecord, $data);
                }
            }
        }
        return false;
    }

    /**
     * DELETE
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
        $result = $this->dbConnection->query($sql);
        if ($result) {
            return true;
        }

        return false;
    }

    public function get()
    {
        return $this->data;
    }

    public function countAllFilter($sql)
    {
        return count($this->getAll($sql));
    }

    public function paginationBase($limit, $sql_all_filter)
    {   
        $data = [];
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($current_page - 1) * $limit;
        $count = $this->countAllFilter($sql_all_filter);
        $pagination_records = $this->getAll($sql_all_filter." LIMIT $start, $limit");
        $data[$this->table] = $pagination_records;
        $data['pagination'] = $this->getPagination($current_page, $count, $limit);
        return $data;
    }

    public function paginationBaseWeb($limit, $sql_all_filter)
    {   
        $data = [];
        $current_page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start = ($current_page - 1) * $limit;
        $count = $this->countAllFilter($sql_all_filter);
        $pagination_records = $this->getAll($sql_all_filter." LIMIT $start, $limit");
        $data[$this->table] = $pagination_records;
        $data['pagination'] = $this->getPaginationWeb($current_page, $count, $limit);
        $data['pagination_total_record'] = $count;
        return $data;
    }

}
