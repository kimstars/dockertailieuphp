<?php

require_once 'app/Models/Model.php';
require_once 'app/Models/Document.php';

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = ['id', 'title', 'active', 'parent_id', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    protected $rawRecursive = [];

    protected $cleanRecursive = [];

    protected $active = 0;

    protected $orderByUser = false;

    public function recursiveAdmin()
    {
        $this->rawCategoryRecursive();
        return $this->rawRecursive;
    }

    public function recursiveUser($id = 0)
    {
        $this->active = 1;
        $this->orderByUser = true;
        $this->rawCategoryRecursive($id);
        $this->cleanCategoryRecursive();
        if ($id != 0 && count($this->cleanRecursive) > 0) {
            $this->cleanRecursive = $this->cleanRecursive[0];
        }
        return $this->cleanRecursive;
    }

    public function rawCategoryRecursive($id = 0, $level = 0)
    {

        $sql = "SELECT * FROM {$this->table}" . ($this->active ? " where active = 1 " : " ") . "order by parent_id, title asc";
        $result = $this->dbConnection->query($sql);
        $all = $result->fetch_all(MYSQLI_ASSOC);

        foreach ($all as $value) {
            if ($value['parent_id'] == $id) {
                $this->rawRecursive[] = array_merge($value, ['level' => $level]);
                $this->rawCategoryRecursive($value['id'], $level + 1);
            }
        }
    }

    public function cleanCategoryRecursive()
    {
        $arr = array_reverse($this->rawRecursive);
        $child = [];
        $level = 0;
        $pre_level = 0;
        foreach ($arr as $key => $value) {
            $level = $value['level'];
            if ($level != 0 && $level == $pre_level) {
                $child[] = $value;
            } else {
                $pre_level = $level;
                $value['childrens'] = $child;
                $child = [];
                if ($level == 0) {
                    $this->cleanRecursive[] = $value;
                } else {
                    $child[] = $value;
                }
            }
            unset($arr[$key]);
        }
    }

    //web
    public function findChildrens($parent_id, $active = true)
    {
        $sql = "SELECT * FROM categories where parent_id = $parent_id";
        if ($active) {
            $sql .= " and active = 1";
        }
        return $this->getAll($sql);
    }

    // web
    public function listActive($limit = -1)
    {
        $sql = "SELECT * FROM categories where active = 1";
        if ($limit != -1) {
            $sql .= " limit $limit";
        }
        $this->data = $this->getAll($sql);
        return $this->data;
    }

    // admin
    public function updateStatusRecursive($data)
    {
        $active = $data['active'];
        $id = $data['id'];
        $updated_at = date('Y-m-d H:i:s');
        $sql = "UPDATE {$this->table} SET active = $active ,`updated_at` = '$updated_at' WHERE {$this->primaryKey} = '$id'";
        $this->dbConnection->query($sql);

        $document_model = new Document();
        $document_model->updateStatusAllByCategoryId($data);
        // get childrens
        $childrens = $this->getAll("SELECT * FROM $this->table WHERE parent_id = '$id'");
        foreach ($childrens as $children) {
            $children['active'] = $active;
            $this->updateStatusRecursive($children);
        }

    }

    // admin
    public function deleteRecursive($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = $id";
        $this->dbConnection->query($sql);

        // get childrens
        $childrens = $this->getAll("SELECT * FROM {$this->table} WHERE parent_id = '$id'");
        foreach ($childrens as $children) {
            $this->deleteRecursive($children['id']);
        }

    }

    // admin
    public function pagination($limit)
    {
        $sql_all_filter = "SELECT * FROM $this->table";
        $filter = '';
        $filter = isset($_GET['s']) ? "AND title like '%{$_GET['s']}%'" : '';
        $sql_all_filter .= " WHERE TRUE $filter";
        return $this->paginationBase($limit, $sql_all_filter);
    }

}
