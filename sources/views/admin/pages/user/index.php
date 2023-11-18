<?php
require_once 'views/admin/layouts/default.php';
?>
<?php startblock('title')?>
    Quản lý người dùng
<?php endblock()?>

<?php startblock('link')?>
    <link href="<?=asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')?>" rel="stylesheet">
<?php endblock()?>

<?php startblock('content')?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quản lý người dùng</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>ClassName</th>
                        <th>Phone</th>
                        <th>Ngày cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?=$user['name']?></td>
                            <td><?=$user['email']?></td>
                            <td><?=$user['class_name']?></td>
                            <td><?=$user['phone']?></td>
                            <td><?=$user['updated_at']?></td>
                        </tr>
                    <?php endforeach?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>ClassName</th>
                        <th>Phone</th>
                        <th>Ngày cập nhật</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?=$pagination?>
    </div>
</div>

<?php endblock()?>
