<?php
require_once 'views/admin/layouts/default.php';
?>
<?php startblock('title')?>
    Quản lý tài liệu
<?php endblock()?>

<?php startblock('link')?>
    <link href="<?=asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')?>" rel="stylesheet">
    <style>
        .text-overflow-ellipses {
            white-space: nowrap;
            width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: block;
        }
    </style>
<?php endblock()?>

<?php startblock('content')?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Tài liệu</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row-reverse">
        <a class="btn btn-warning" href="<?=url('admin/document/handleSyncFileDrive')?>" class="btn btn-sm mb-0 btn-info" >Đồng bộ với Drive</a>
        <a class="btn btn-primary mr-2" href="<?=url('admin/document/create')?>">Tạo tài liệu</a>
    </div>
    <form>
        <div class="card-header py-3 d-flex align-items-center">
            <div class="form-group" style="width: 15%;">
                <label for="filter-cat">Danh mục</label>
                <select class="form-control" name="category_id" id="filter-cat">
                    <option value="">Tất cả</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= isset($_GET['category_id']) && $_GET['category_id'] == $category['id'] ? 'selected' : '' ?>><?= str_repeat('---|', $category['level']) . ' ' . $category['title'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group ml-4">
                <label for="filter-date">Ngày tạo</label>
                <select class="form-control" name="date" id="filter-date">
                    <option value="">Chọn</option>
                    <option value="desc" <?= isset($_GET['date']) && $_GET['date'] == 'desc' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="asc" <?= isset($_GET['date']) && $_GET['date'] == 'asc' ? 'selected' : '' ?>>Cũ nhất</option>
                </select>
            </div>
            <button type="submit" class="btn btn-info ml-4">Lọc</button>
        </div>
    </form>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Danh mục</th>
                        <th>Xem trước</th>
                        <th>Tên tài liệu</th>
                        <th>Tên file</th>
                        <th>Người tạo</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($documents as $document): ?>
                        <tr>
                            <td class="align-middle"><?=$document['category_parent_title'] ? "{$document['category_parent_title']} > <br/>" : ''?><?=$document['category_title']?></td>
                            <td class="align-middle">
                                <img src="https://lh3.googleusercontent.com/d/<?=$document['upload_id']?>" style="height: 100px; width: auto" >
                            </td>
                            <td class="align-middle"><?=$document['name']?></td>
                            <td class="align-middle">
                                <a class="text-overflow-ellipses" href="https://drive.google.com/open?id=<?=$document['upload_id']?>" target="_blank"> <?=$document['file_name']?> </a>
                            </td>
                            <td class="align-middle"><?=$document['is_admin_upload'] == 1 ? 'Admin' : 'User'?></td>
                            <td class="align-middle" style="min-width: 120px;">
                                <input id="status_<?=$document['id']?>" name="status" type="checkbox" <?=$document['active'] == 1 ? 'checked' : ''?> data-active="<?=$document['active']?>" data-id="<?=$document['id']?>" onclick="changeStatus(this)">
                                <label for="status_<?=$document['id']?>"><?=$document['active'] == 1 ? 'Đang hiện' : 'Đang ẩn'?></label>
                            </td>
                            <td class="align-middle">
                                <?=dateFromStr($document['created_at'], "d/m/Y H:i:s")?>
                            </td>
                            <td class="align-middle">
                                <?=dateFromStr($document['updated_at'], "d/m/Y H:i:s")?>
                            </td>
                            <td class="align-middle" style="min-width: 100px;">
                                <a href="<?=url('admin/document/update/') . $document['id']?>" class="btn btn-sm mb-0 btn-info" >
                                    Cập nhật
                                </a>
                                <button onclick="confirmDelete(event.target.dataset.param)"  data-param="<?php echo htmlspecialchars(json_encode($document)) ?>" type="button" class="btn btn-sm mb-0 btn-danger mt-sm-2">
                                    Xoá
                                </button>
                            </td>
                        </tr>
                    <?php endforeach?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Danh mục</th>
                        <th>Xem trước</th>
                        <th>Tên tài liệu</th>
                        <th>Tên file</th>
                        <th>Người tạo</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?=$pagination?>
    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Xác nhận</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn chắc chắn muốn xoá bản ghi này không?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Thoát</button>
                <a class="btn btn-danger" onclick="handleDelete()">Xoá</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chi tiết bản ghi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table>
                    <tbody>
                        <tr>
                            <td>Mô tả: </td>
                            <td id="detail_description"></td>
                        </tr>
                        <tr>
                            <td>File name: </td>
                            <td id="detail_file_name"></td>
                        </tr>
                        <tr>
                            <td>File size: </td>
                            <td id="detail_file_size"></td>
                        </tr>
                        <tr>
                            <td>File type: </td>
                            <td id="detail_file_type"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="mt-3">
                    <button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Drive detail
                    </button>
                    <div class="collapse mt-3" id="collapseExample">
                        <pre id="detail_drive"></pre>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>
<?php endblock()?>

<?php startblock('script')?>
<script>
    var data = {}
    function changeStatus(e) {
        let id = e.getAttribute('data-id')
        let active = 1 -e.getAttribute('data-active')
        window.location.href = `<?=url('admin/document/status')?>?id=${id}&active=${active}`
    }
    function confirmDelete(raw_data) {
        data = JSON.parse(raw_data)
        $('#confirmDeleteModal').modal('show')
    }
    function handleDelete() {
        $('#confirmDeleteModal').modal('hide')
        window.location.href = '<?=url('admin/document/handleDelete')?>?id=' + data.id
    }
    function openDetailModal(raw_data) {
        $('#detail_drive').text('')
        $('#collapseExample').collapse('hide')
        data = JSON.parse(raw_data)
        for (i in data) {
            $(`#detail_${i}`).text(data[i])
        }
        $.get(`<?=url('admin/document/detail')?>?id=${data.file_id}`)
            .done((res, status) => {
                const result = JSON.parse(res)
                const data = result.data
                $('#detail_drive').text(JSON.stringify(data.upload, undefined, 2))
                for (i in data) {
                    $(`#detail_${i}`).text(data[i])
                }
                $('#detailModal').modal('show')
            })
            .fail((xhr, status, error) => {
                console.log(error);
            });

    }
</script>
<?php endblock()?>