<?php 
require_once('app/Models/Model.php');

class File extends Model
{
    protected $table = 'files';

    protected $fillable = ['id', 'upload_id', 'file_name', 'file_type', 'file_size','created_at', 'updated_at'];

    protected $primaryKey = 'id';

    public function findByID($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} =`$id`" ;
        return $this->getAll($sql);
    }
}
