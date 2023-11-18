<?php

use Google\Service\Drive;
use Google\Service\Drive\DriveFile;
use Google\Service\Drive\Permission;

require_once 'app/Models/File.php';

class UploadService
{
    protected $client;
    protected $clientSecretPath = './config/secret.json';

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig($this->clientSecretPath);

    }
    public function list()
    {
        $this->client->addScope(Drive::DRIVE_READONLY);
        $driveService = new Drive($this->client);
        // List files from Google Drive
        $results = $driveService->files->listFiles(array(
            'q' => "'me' in owners",
            'fields' => "files(id, name, mimeType, thumbnailLink, size)",
            // List files owned by your account
        ));
        return $results->getFiles();
    }

    public function get($file_id)
    {
        $this->client->addScope(Drive::DRIVE_READONLY);
        $driveService = new Drive($this->client);
        try {
            return $driveService->files->get($file_id, array('fields' => 'id, name, webViewLink, webContentLink, createdTime, fileExtension, viewedByMeTime, iconLink,shared'));
        } catch (Google_Service_Exception $exception) {
            throw $exception;
        }
    }

    public function upload($file)
    {
        // validate file
        $allowTypes = array('pdf', 'doc', 'docx', 'jpg', 'png', 'jpeg');
        if (!$file['error'] == 0) {
            throw new Exception('Lỗi File, vui lòng chọn file khác');
        }
        $path = pathinfo($file['name'], PATHINFO_EXTENSION);
        if (!in_array($path, $allowTypes)) {
            throw new Exception("Vui lòng upload file có định dạng: " . implode(', ', $allowTypes));
        }
        if ($file['size'] > 10485760) {
            throw new Exception('File phải nhỏ hơn 10mb');
        }
        // $file['name'] = $file['name']."_".date('Ymdhis');

        // upload file
        $this->client->addScope(Drive::DRIVE);
        $driveService = new Drive($this->client);
        $fileName = basename($file['name']);
        $filePath = $file['tmp_name'];
        $fileMetadata = new DriveFile([
            'name' => $fileName,
        ]);
        $minetype = mime_content_type($filePath);
        // upload file to Google Drive
        try {
            $content = file_get_contents($filePath);
            $upload_file = $driveService->files->create($fileMetadata, [
                'data' => $content,
                'mimeType' => $minetype,
                'uploadType' => 'multipart',
                'fields' => 'id',
            ]);
            // The ID of the uploaded file
            $upload_file_id = $upload_file->id;

            // Set permissions to allow anyone to read the file
            $permission = new Permission([
                'type' => 'anyone',
                'role' => 'reader',
            ]);
            $driveService->permissions->create($upload_file_id, $permission);

            $data_file = [
                'upload_id' => $upload_file_id,
                'file_size' => $file['size'],
                'file_type' => $minetype,
                'file_name' => $file['name'],
            ];

            return $data_file;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function delete($documentId)
    {
        try {
            $document_model = new Document;
            $document = $document_model->find($documentId);
            if ($document) {
                $document_model->delete($documentId);
                try {
                    $this->client->addScope(Drive::DRIVE);
                    $driveService = new Drive($this->client);
                    $driveService->files->delete($document['upload_id']);
                } catch (Exception $e) {}
                return true;
            }
            return false;
        } catch (Google_Service_Exception $e) {
            throw $e;
        }
    }

    public function deleteByUploadId($upload_id)
    {
        try {
            $this->client->addScope(Drive::DRIVE);
            $driveService = new Drive($this->client);
            $driveService->files->delete($upload_id);
            return true;
        } catch (Google_Service_Exception $e) {
            throw $e;
        }
    }
}
