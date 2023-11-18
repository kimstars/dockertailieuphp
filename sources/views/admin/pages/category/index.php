<?php
require_once 'views/admin/layouts/default.php';
?>
<?php startblock('title')?>
    Quản lý danh mục
<?php endblock()?>

<?php startblock('link')?>
    <link href="<?=asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')?>" rel="stylesheet">
<?php endblock()?>

<?php startblock('content')?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Danh mục</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row-reverse">
        <button class="btn btn-primary" onclick="openCreateModal()">Tạo danh mục</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tên danh mục</th>
                        <th>Trạng thái</th>
                        <!-- <th>Ngày tạo</th> -->
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= str_repeat('---|', $category['level']) . ' ' . $category['title']?></td>
                            <td>
                                <input id="status_<?=$category['id']?>" name="status" type="checkbox" <?=$category['active'] == 1 ? 'checked' : ''?> data-active="<?=$category['active']?>" data-id="<?=$category['id']?>" onclick="changeStatus(this)">
                                <label for="status_<?=$category['id']?>"><?=$category['active'] == 1 ? 'Đang hiện' : 'Đang ẩn'?></label>
                            </td>
                            <!-- <td>
                                <?=dateFromStr($category['created_at'], "d/m/Y H:i:s")?>
                            </td> -->
                            <td>
                                <?=dateFromStr($category['updated_at'], "d/m/Y H:i:s")?>
                            </td>
                            <td class="align-middle">
                                <button onclick="openEditModal(event.target.dataset.param)"  data-param="<?php echo htmlspecialchars(json_encode($category)) ?>" type="button" class="btn btn-sm mb-0 btn-info" >
                                    Cập nhật
                                </button>
                                <button onclick="confirmDelete(event.target.dataset.param)"  data-param="<?php echo htmlspecialchars(json_encode($category)) ?>" type="button" class="btn btn-sm mb-0 btn-danger" >
                                    Xoá
                                </button>
                            </td>
                        </tr>
                    <?php endforeach?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Tên danh mục</th>
                        <th>Trạng thái</th>
                        <!-- <th>Ngày tạo</th> -->
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php
            // $pagination
        ?>
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
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Chỉnh sửa bản ghi</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Chọn danh mục cha</label>
                    <select id="form_parent_id" class="form-control">
                        <option value="0">Chọn...</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= str_repeat('---|', $category['level']) . ' ' . $category['title'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="form_title">Tiêu đề</label>
                    <input id="form_title" type="text" class="form-control">
                    <div id="invalid_title" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox small d-flex align-items-center">
                        <input type="checkbox" id="form_active" class="custom-control-input" >
                        <label class="custom-control-label" for="form_active">Trạng thái</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Thoát</button>
                <a class="btn btn-primary" onclick="handleSave()">Lưu</a>
            </div>
        </div>
    </div>
</div>
<?php endblock()?>

<?php startblock('script')?>
<script>
    var data = {
        id: '',
        title: '',
        parent_id: '0',
        active: '0',
    }
    var is_create = true
    function changeStatus(e) {
        let id = e.getAttribute('data-id')
        let active = 1 -e.getAttribute('data-active')
        window.location.href = `<?=url('admin/category/status')?>?id=${id}&active=${active}`
    }
    async function parseDataToEditModal() {
        for (i in data) {
            if (i == 'active') {
                $('#form_active').prop('checked', data[i] == '1');
            } else {
                $(`#form_${i}`).val(data[i])
            }
        }
    }
    async function getDataFromModal() {
        let new_data = {}
        for (i in data) {
            if (i == 'active') {
                new_data[i] = $(`#form_${i}`).is(":checked") ? '1' : '0'
            } else {
                if ($(`#form_${i}`).val() != undefined) {
                    new_data[i] = $(`#form_${i}`).val()
                }
            }
        }
        data = { ...data, ...new_data }
    }
    async function resetValidate() {
        for (i in data) {
            $(`#form_${i}`).removeClass('is-invalid')
            $(`#invalid_${i}`).text('')
        }
    }
    async function handleSave() {
        await getDataFromModal()
        await resetValidate()
        $.post(`<?= url('admin/category') ?>/${is_create ? 'handleCreate' : 'handleUpdate'}`, data)
            .done((data, status) => {
                window.location.reload()
            })
            .fail((xhr, status, error) => {
                const res = JSON.parse(xhr.responseText)
                const data = res.data
                for (i in data) {
                    $(`#form_${i}`).addClass('is-invalid')
                    $(`#invalid_${i}`).text(data[i])
                }
            });
    }
    async function openCreateModal() {
        data = {
            title: '',
            parent_id: '0',
            active: '0',
        }
        is_create = true
        await resetValidate()
        await parseDataToEditModal()
        $('#editModal').modal('show')
    }
    async function openEditModal(raw_data) {
        data = JSON.parse(raw_data)
        is_create = false
        await resetValidate()
        await parseDataToEditModal()
        $('#editModal').modal('show')
    }
    function confirmDelete(raw_data) {
        data = JSON.parse(raw_data)
        console.log(data);
        $('#confirmDeleteModal').modal('show')
    }
    function handleDelete() {
        $('#confirmDeleteModal').modal('hide')
        window.location.href = '<?=url('admin/category/handleDelete')?>?id=' + data.id
    }
</script>
<?php endblock()?>