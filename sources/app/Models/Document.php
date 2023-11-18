<?php

require_once 'app/Models/Model.php';
require_once 'app/Models/File.php';
require_once 'app/Services/UploadService.php';

class Document extends Model
{
    protected $table = 'documents';

    protected $fillable = ['id', 'category_id', 'upload_id', 'file_size', 'file_type', 'file_name', 'view_count', 'user_id', 'is_admin_upload', 'active', 'name', 'description', 'created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public function all()
    {
        $sql = "SELECT documents.*, categories.title as 'category_title' FROM `documents` INNER JOIN categories ON categories.id = documents.category_id";
        return $this->getAll($sql);
    }

    // admin
    public function updateStatus($data)
    {
        $updated_at = date('Y-m-d H:i:s');
        $sql = "UPDATE documents SET active='{$data['active']}' ,`updated_at`='$updated_at' WHERE `id`='{$data['id']}'";
        return $this->dbConnection->query($sql);
    }

    public function updateStatusAllByCategoryId($data)
    {
        $category_id = $data['id'];
        $active = $data['active'];
        $updated_at = date('Y-m-d H:i:s');
        $sql = "UPDATE documents SET active='$active' ,`updated_at`='$updated_at' WHERE `category_id`='$category_id'";
        return $this->dbConnection->query($sql);
    }

    public function updateViewCount($doc_id)
    {
        $sql = "SELECT update_document_view_count($doc_id)";
        return $this->dbConnection->query($sql);
    }

    public function updateDownloadCount($doc_id)
    {
        $sql = "SELECT update_document_download_count($doc_id)";
        return $this->dbConnection->query($sql);
    }

    public function getDocByUploadID($file_id)
    {
        $sql = "SELECT upload_id FROM documents where id=$file_id";
        return $this->getFirst($sql);
    }

    public function getDocByUserID($user_id)
    {
        $sql = "SELECT * FROM documents WHERE user_id = $user_id";
        return $this->getAll($sql);
    }



    // SELECT * FROM documents ORDER BY view_count DESC LIMIT 10;
    public function getMostViewedDocuments()
    {
        $sql = "SELECT * FROM documents ORDER BY view_count DESC LIMIT 10";
        return $this->getAll($sql);
    }

    public function getMostDownloadedDocuments()
    {
        $sql = "SELECT * FROM documents ORDER BY download_count DESC LIMIT 10";
        return $this->getAll($sql);
    }

    public function getRecentlyAddedDocuments() //trả về các tài liệu được thêm gần đây.
    {
        $sql = "SELECT * FROM document ORDER BY created_at DESC LIMIT 10";
        return $this->getAll($sql);
    }


    public function listByCategoryId($id, $active = false)
    {
        $sql = "SELECT
            cat1.title as 'category_title',
            cat2.title as 'category_parent_title',
            documents.*
            FROM `documents`
            INNER JOIN categories as cat1 ON cat1.id = documents.category_id
            LEFT JOIN categories as cat2 ON cat1.parent_id = cat2.id
            WHERE documents.category_id = $id";
        if ($active) {
            $sql .= " AND documents.active = 1";
        }
        return $this->getAll($sql);
    }

    public function bySearch($search)
    {
        $sql = "SELECT * FROM `documents` WHERE name like '%$search%' OR file_name LIKE '%$search%'";
        if (isset($_GET['filter'])) {
            $filter = explode('-', $_GET['filter']);
            if (count($filter) == 2) {
                $sql .= " ORDER BY {$filter[0]} {$filter[1]}";
            }
        }
        return $this->getAll($sql);
    }

    public function relative($category_id, $id, $limit)
    {
        $sql = "SELECT * FROM `documents` WHERE category_id = '$category_id' AND id != '$id' ORDER BY RAND() LIMIT $limit";
        return $this->getAll($sql);
    }

    // admin
    public function pagination($limit)
    {
        $sql_all_filter = "SELECT
            documents.*,
            cat1.title as 'category_title',
            cat2.title as 'category_parent_title'
            FROM `documents`
            INNER JOIN categories as cat1 ON cat1.id = documents.category_id
            LEFT JOIN categories as cat2 ON cat1.parent_id = cat2.id";
        
        $filter = " WHERE TRUE";
        if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
            $filter .= " AND documents.category_id = {$_GET['category_id']}";
        }
        if (isset($_GET['date']) && in_array($_GET['date'], ['desc', 'asc'])) {
            $filter .= " ORDER BY created_at {$_GET['date']}";
        }
        $sql_all_filter .= $filter;
        return $this->paginationBase($limit, $sql_all_filter);
    }

    // web
    public function paginationWeb($limit, $category_id = -1)
    {
        $sql_all_filter = "SELECT documents.* FROM `documents` WHERE active = 1";
        if ($category_id > 0) {
            $sql_all_filter .= " AND category_id = '$category_id'";
        }
        if (isset($_GET['q'])) {
            $sql_all_filter .= " AND name like '%{$_GET['q']}%'";
        }
        if (isset($_GET['sort_by'])) {
            $filter = explode('-', $_GET['sort_by']);
            if (count($filter) == 2) {
                $sql_all_filter .= " ORDER BY {$filter[0]} {$filter[1]}";
            }
        }
        return $this->paginationBaseWeb($limit, $sql_all_filter);
    }

    public function paginationSearchWeb($limit, $search)
    {
        $sql_all_filter = "SELECT * FROM `documents` WHERE name like '%$search%'";
        if (isset($_GET['filter'])) {
            $filter = explode('-', $_GET['filter']);
            if (count($filter) == 2) {
                $sql_all_filter .= " ORDER BY {$filter[0]} {$filter[1]}";
            }
        }
        return $this->paginationBaseWeb($limit, $sql_all_filter);
    }
}
