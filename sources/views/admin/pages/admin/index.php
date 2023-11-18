<?php
require_once 'views/admin/layouts/default.php';
?>
<?php startblock('title')?>
    Quản lý admin
<?php endblock()?>

<?php startblock('link')?>
    <link href="<?=asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')?>" rel="stylesheet">
<?php endblock()?>

<?php startblock('content')?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quản lý admin</h1>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex flex-row-reverse">
        <button class="btn btn-primary" onclick="openCreateModal()">Tạo mới</button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td><?= $user['id'] ?></td>
                            <td><?= $user['name'] ?></td>
                            <td><?= $user['email'] ?></td>
                            <td><?= $user['updated_at'] ?></td>
                            <td class="align-middle">
                                <button onclick="openEditModal(event.target.dataset.param)"  data-param="<?php echo htmlspecialchars(json_encode($user)) ?>" type="button" class="btn btn-sm mb-0 btn-info" >
                                    Cập nhật
                                </button>
                                <button onclick="confirmDelete(event.target.dataset.param)"  data-param="<?php echo htmlspecialchars(json_encode($user)) ?>" type="button" class="btn btn-sm mb-0 btn-danger" >
                                    Xoá
                                </button>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Ngày cập nhật</th>
                        <th>Hành động</th>
                    </tr>
                </tfoot>
            </table>
        </div>
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
                <p>Bạn chắc chắn muốn xoá người dùng này không?</p>
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
                    <label for="form_name">Tên</label>
                    <input id="form_name" type="text" class="form-control">
                    <div id="invalid_name" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="form_email">Email</label>
                    <input id="form_email" type="text" class="form-control">
                    <div id="invalid_email" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="form_password">Mật khẩu</label>
                    <input id="form_password" type="password" class="form-control">
                    <div id="invalid_password" class="invalid-feedback"></div>
                </div>
                <div class="form-group">
                    <label for="form_password_confirmation">Xác nhận mật khẩu</label>
                    <input id="form_password_confirmation" type="password" class="form-control">
                    <div id="invalid_password_confirmation" class="invalid-feedback"></div>
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
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    }
    var is_create = true

    async function parseDataToEditModal() {
        for (i in data) {
            if (!i.includes('password')) {
                $(`#form_${i}`).val(data[i])
            } else {
            }
        }
    }
    async function getDataFromModal() {
        let new_data = {}
        for (i in data) {
            if ($(`#form_${i}`).val() != undefined) {
                new_data[i] = $(`#form_${i}`).val()
            }
        }
        data = { ...data, ...new_data }
    }
    async function handleSave() {
        await getDataFromModal()
        await resetValidate()
        $.post(`<?= url('admin/admin') ?>/${is_create ? 'handleCreate' : 'handleUpdate'}`, data)
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
    async function resetValidate() {
        for (i in data) {
            $(`#form_${i}`).removeClass('is-invalid')
            $(`#invalid_${i}`).text('')
        }
    }
    async function openCreateModal() {
        data = {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        }
        is_create = true
        await resetValidate()
        await parseDataToEditModal()
        $('#form_email').attr('disabled', false)
        $('#editModal').modal('show')
    }

    async function openEditModal(raw_data) {
        data = JSON.parse(raw_data)
        data.oldPassword = data.password
        data.password_confirmation = ''
        is_create = false
        await resetValidate()
        await parseDataToEditModal()
        $('#form_email').attr('disabled', true)
        $('#editModal').modal('show')
    }
    

    function confirmDelete(raw_data) {
        data = JSON.parse(raw_data)
        $('#confirmDeleteModal').modal('show')
    }
    function handleDelete() {
        $('#confirmDeleteModal').modal('hide')
        window.location.href = '<?=url('admin/user/handleDelete')?>?id=' + data.id
    }
</script>
<?php endblock()?>