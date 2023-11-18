<?php

require_once 'app/Controllers/Admin/BackendController.php';
require_once 'app/Requests/DocumentRequest.php';
require_once 'app/Models/Document.php';
require_once 'app/Models/Category.php';
require_once 'core/Flash.php';
require_once 'core/Storage.php';
require_once 'core/Auth.php';
require_once 'app/Services/UploadService.php';
class DocumentController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware->handle();
    }

    public function index()
    {
        $this->setMethod('GET');
        $document_model = new Document();
        $category_model = new Category();
        $categories = $category_model->recursiveAdmin();
        $documents = $document_model->pagination(10);
        return $this->view("pages/document/index.php", array_merge($documents, ['categories' => $categories]));
    }

    public function create()
    {
        $this->setMethod('GET');
        $category_model = new Category();
        $categories = $category_model->recursiveAdmin();
        return $this->view("pages/document/edit.php", ['categories' => $categories, 'is_create' => true]);
    }

    public function update()
    {
        $this->setMethod('GET');
        if (isset($_GET['id'])) {
            $document_model = new Document();
            $document = $document_model->find($_GET['id']);
            $category_model = new Category();
            $categories = $category_model->recursiveAdmin();
            return $this->view("pages/document/edit.php", ['document' => $document, 'categories' => $categories, 'is_create' => false]);
        }
        redirect('admin/document');
    }

    public function handleCreate()
    {
        $this->setMethod('POST');
        $request = new DocumentRequest();
        $errors = $request->validateCreate($_POST);
        try {
            $file = $_FILES['file'];
            if (empty($errors)) {

                $uploadService = new UploadService();
                $data_file = $uploadService->upload($file);

                $document_model = new Document();
                $data = $_POST;
                $data['active'] = isset($_POST['active']);
                $data['is_admin_upload'] = true;
                $data['user_id'] = Auth::getUser('admin')['id'];
                $data['upload_id'] = $data_file['upload_id'];
                $data['file_size'] = $data_file['file_size'];
                $data['file_type'] = $data_file['file_type'];
                $data['file_name'] = $data_file['file_name'];
                $data['view_count'] = 0;

                if ($document_model->create($data)) {
                    Flash::set('success', 'Tạo tài liệu thành công!');
                    return redirect('admin/document');
                }
            }
        } catch (Exception $e) {
            $errors['form_data'] = $_POST;
            Flash::set('errors', $errors);
            Flash::set('error', $e->getMessage());
            return back();
        }
    }

    public function handleSyncFileDrive()
    {
        $this->setMethod('GET');
        $request = new DocumentRequest();
        $errors = $request->validateSync($_GET);
        if (empty($errors)) {
            $uploadService = new UploadService();
            $document_model = new Document();
            $sql = "SELECT * FROM documents";
            $list_DB = $document_model->getAll($sql);
            $uploadService = new UploadService();
            $listFileDrive = $uploadService->list();
            // lấy phần tử không có trên db nhưng có trên drive
            $result = [];
            foreach ($listFileDrive as $item2) {
                $found = false;
                foreach ($list_DB as $item1) {
                    if ($item1['upload_id'] === $item2['id']) {
                        $found = true;
                        break;
                    }
                }
                if (!$found) {
                    $result[] = $item2;
                }
            }
            $document_model = new Document();
            foreach ($result as $data_file) {
                $data = null;
                $data['active'] = true;
                $data['is_admin_upload'] = true;
                $data['category_id'] = null;
                $data['user_id'] = Auth::getUser('admin')['id'];
                $data['upload_id'] = $data_file['id'];
                $data['file_size'] = $data_file['size'];
                $data['file_type'] = $data_file['mimeType'];
                $data['file_name'] = $data_file['name'];
                $data['view_count'] = 0;
                $document_model->create($data);
            }
            Flash::set('success', 'Đồng bộ tài liệu thành công!');
        }
        return redirect('admin/document');
    }

    public function handleUpdate()
    {
        $this->setMethod('POST');
        $request = new DocumentRequest();
        $errors = $request->validateUpdate($_POST);
        try {
            if (empty($errors)) {
                $data = $_POST;
                $id = $data['id'];
                unset($data['id']);

                $document_model = new Document();
                $document = $document_model->find($id);

                $file = $_FILES['file'];
                if ($file['error'] > 0) {
                    // file not found
                    $file_id = $document['upload_id'];
                } else {
                    $uploadService = new UploadService;
                    $uploadService->delete($document['upload_id']);
                    $file_id = $uploadService->upload($file);
                }
                $data['upload_id'] = $file_id;
                if ($document_model->update($data, $id)) {
                    Flash::set('success', 'Cập nhật tài liệu thành công!');
                    return redirect('admin/document');
                }
            }
        } catch (Exception $e) {
            $errors['form_data'] = $_POST;
            Flash::set('errors', $errors);
            Flash::set('error', $e->getMessage());
            return back();
        }
    }

    public function status()
    {
        $this->setMethod('GET');
        $request = new DocumentRequest();
        $errors = $request->validateStatus($_GET);
        if (empty($errors)) {
            $product_model = new Document();
            $product_model->updateStatus($_GET);
            Flash::set('success', 'Cập nhật trạng thái tài liệu thành công!');
        }
        return redirect('admin/document');
    }

    public function handleDelete()
    {
        $this->setMethod('GET');
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $uploadService = new UploadService();
            $id = $_GET['id'];
            try {
                $uploadService->delete($id);
                Flash::set('success', 'Xoá tài liệu thành công!');
                return redirect('admin/document');
            } catch (Exception $e) {
                Flash::set('error', 'Xoá tài liệu thất bại!');
                return back();
            }
        };
    }



    // https://drive.google.com/uc?id=1c8vwQWI-LwXif9wanDjysM8O_DZbPVHN&export=download



}
