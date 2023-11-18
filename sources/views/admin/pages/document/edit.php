<?php
require_once 'views/admin/layouts/default.php';
?>
<?php startblock('title')?>
    <?= $is_create ? 'Tạo tài liệu' : 'Chỉnh sửa tài liệu' ?>
<?php endblock()?>

<?php startblock('link')?>
    <link href="<?=asset('admin/vendor/datatables/dataTables.bootstrap4.min.css')?>" rel="stylesheet">
<?php endblock()?>

<?php startblock('content')?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= $is_create ? 'Tạo tài liệu' : 'Chỉnh sửa tài liệu' ?></h1>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <form onsubmit="return checkSubmit()" method="post" action="<?=url('admin/document/' . ($is_create ? 'handleCreate' : 'handleUpdate'))?>" enctype="multipart/form-data">
            <?= !$is_create ? "<input type='hidden' name='id' value='{$document['id']}' />" : '' ?>
            <div class="form-group">
                <label for="">Chọn danh mục</label>
                <select class="form-control" name="category_id" required>
                    <option value="">Chọn...</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= isset($document) && $document['category_id'] == $category['id'] ? 'selected' : '' ?>><?= str_repeat('---|', $category['level']) . ' ' . $category['title'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label for="">Tên tài liệu</label>
                <input type="text" class="form-control" name="name" required value="<?= isset($document) ? $document['name'] : '' ?>">
            </div>

            

            <div class="form-group">
                <label for="">Mô tả tài liệu</label>
                <textarea type="text" class="form-control" name="description" required><?= isset($document) ? $document['description'] : '' ?></textarea>
            </div>
            

            <div class="custom-file mt-3">
                <input onchange="resetValidate(); validateFile(this)" type="file" class="custom-file-input" id="form_file" name="file" <?= $is_create ? 'required' : '' ?>>
                <label class="custom-file-label" id="form_file_label" for="form_file"><?= $is_create ? 'Chọn file...' : $document['file_name'] ?></label>
                <div class="invalid-feedback" id="form_file_error">Example invalid custom file feedback</div>
            </div>
            <div class="mt-3 d-flex flex-row-reverse">
                <button type="submit" class="btn btn-primary"><?= $is_create ? 'Tạo mới' : 'Cập nhật' ?></button>
            </div>
        </form>
    </div>
</div>
<?php endblock()?>

<?php startblock('script')?>
<script>
    var checkValidate = <?= $is_create ? 'true' : 'false' ?>;
    
    function checkSubmit() {
        if (!validateFile($('#form_file')[0])) {
            console.log('error file');
            return false
        }
        return true
    }
    function resetValidate() {
        checkValidate = true
        $('#form_file').removeClass('is-invalid')
        $('#form_file_error').text('')
        $('#form_file_label').text('Chọn file...')
    }
    function validateFile(e) {
        if (!checkValidate) {
            return true
        }
        let allowTypes = ['pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg'];
        if (e.files && e.files[0]) {
            let file = e.files[0]
            let url = e.value
            let ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
            $('#form_file_label').text(file.name)
            if (!allowTypes.includes(ext)) {
                $('#form_file').addClass('is-invalid')
                $('#form_file_error').text(`Vui lòng upload file có định dạng: ${allowTypes.join(', ')}`)
                return false
            }
            if (file.size > 10485760) {
                $('#form_file').addClass('is-invalid')
                $('#form_file_error').text('File phải nhỏ hơn 10mb')
                return false
            }
            return true
        } else {
            $('#form_file').addClass('is-invalid')
            $('#form_file_error').text('Vui lòng chọn file')
        }
    }
    
</script>
<?php endblock()?>