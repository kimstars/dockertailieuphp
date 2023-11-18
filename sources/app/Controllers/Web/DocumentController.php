<?php

require_once 'app/Controllers/Web/WebController.php';
require_once 'app/Models/Document.php';
require_once 'app/Models/Category.php';
require_once 'app/Services/UploadService.php';
require_once 'app/Requests/DocumentRequest.php';
require_once 'core/JWTAuth.php';
require_once 'core/Storage.php';

class DocumentController extends WebController
{

    public function __construct()
    {
        $this->middleware('ApiAuthenticationMiddleware', [
            'excepts' => ['index', 'detail', 'list', 'listByCategoryId', 'mostViewedDocuments', 'mostDownloadedDocuments', 'mostDownloadedDocuments', 'recentlyAddedDocuments','search' ],
        ]);
    }
    public function index()
    {
        try {
            if ($this->isMethod('GET')) {
                if (!isset($_GET['id'])) {
                    throw new Exception('id not found!');
                }
                $id = $_GET['id'];
                if (!$id) {
                    throw new Exception('Invalid ID supplied', 400);
                }
                $document_model = new Document();
                $document = $document_model->find($id);
                $document_model->updateViewCount($id);
                if (!$document) {
                    throw new Exception('Document not found!', 404);
                }
                $this->handleSuccessJsonResponse($document);
            }
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }

    public function list()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document();
            $documents = $document_model->findAll();
            $this->handleSuccessJsonResponse($documents);
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }

    public function listByCategoryId()
    {
        try {
            $this->setMethod('GET');
            $data = $this->getRequestData();
            $id = $data['id'];
            $category_model = new Category();
            $document_model = new Document();
            $category = $category_model->find($id);
            if (!$category) {
                throw new Exception('Category not found');
            }
            $documents = [];
            $parent_documents = $document_model->listByCategoryId($id, true);
            $documents = array_merge($documents, $parent_documents);
            $childrens = $category_model->findChildrens($id);
            foreach ($childrens as $children) {
                $child_documents = $document_model->listByCategoryId($children['id'], true);
                $documents = array_merge($documents, $child_documents);
            }
            $this->handleSuccessJsonResponse($documents);
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }

    public function upload()
    {
        $request = new DocumentRequest();
        $errors = $request->validateCreate($_POST);
        try {
            $this->setMethod('POST');
            $file = $_FILES['file'];
            if (empty($errors)) {
                $uploadService = new UploadService();
                $data_file = $uploadService->upload($file);
                $document_model = new Document();
                $data = $_POST;
                $data['active'] = isset($_POST['active']);
                $data['is_admin_upload'] = false;
                $data['user_id'] = JWTAuth::getUser()['id'];
                $data = array_merge($data, $data_file);
                $data['view_count'] = 0;
                if ($message = $document_model->create($data)) {
                    $this->handleSuccessJsonResponse($message);
                }
            }
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
            return back();
        }
    }

    public function generateLinkDownload()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document;
            $documentId = $_GET['id'];
            $document = $document_model->find($documentId);
            if (!$document) {
                throw new Exception('Document not found', 404);
            }
            $content_file = file_get_contents("https://drive.google.com/uc?id={$document['upload_id']}&export=download");
            $extension = pathinfo($document['file_name'], PATHINFO_EXTENSION);
            $fileName =  md5($document['file_name'] . now()) . "." . $extension;
            Storage::uploadFromContent('temp', $fileName, $content_file);
            $link = asset('storage/temp/' . $fileName);
            $document_model->updateDownloadCount($documentId);
            $this->handleSuccessJsonResponse([
                'link' => $link
            ]);
        } catch (Exception $e) {
            $this->handleExceptionJsonResponse($e);
        }
    }

    public function mostViewedDocuments()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document;

            $data = $document_model->getMostViewedDocuments();
            if ($data != null) {
                $this->handleSuccessJsonResponse($data);
            } else {
                $this->ajax(["message" => "Database empty"], 400);
            }
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function mostDownloadedDocuments()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document;

            $data = $document_model->getMostDownloadedDocuments();
            if ($data != null) {
                $this->handleSuccessJsonResponse($data);
            } else {
                $this->ajax(["message" => "Database empty"], 400);
            }
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function recentlyAddedDocuments()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document;
            $data = $document_model->getMostDownloadedDocuments();
            if ($data != null) {
                $this->handleSuccessJsonResponse($data);
            } else {
                $this->ajax(["message" => "Database empty"], 400);
            }
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function search()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document;
            $keyword = $_GET['k'];
            $data = $document_model->bySearch($keyword);

            if ($data != null) {
                $this->handleSuccessJsonResponse($data);
            } else {
                $this->ajax(["message" => "Database empty"], 400);
            }
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }

    public function myUploadFiles()
    {
        try {
            $this->setMethod('GET');
            $document_model = new Document;
            $user_id = JWTAuth::getUser()['id'];;
            $data = $document_model->getDocByUserID($user_id);

            if ($data != null) {
                $this->handleSuccessJsonResponse($data);
            } else {
                $this->ajax(["message" => "Database empty"], 400);
            }
        } catch (Exception $e) {
            $this->ajax(['message' => $e->getMessage()], 500);
        }
    }




}
