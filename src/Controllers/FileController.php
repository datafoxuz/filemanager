<?php

namespace Cyberbrains\Filamanager\Controllers;
use Cyberbrains\Filamanager\UploadFileService;
use Exception;

class FileController extends ApiController
{

    private UploadFileService $fileService;

    public function __construct()
    {
        $this->fileService = new UploadFileService();
    }

    public function upload()
    {
        try {
            $model = $this->fileService->upload($request->file('file'));
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        return $this->sendResponse($model, 'File successfully uploaded');
    }
}