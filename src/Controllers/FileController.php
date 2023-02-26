<?php

namespace Cyberbrains\Filemanager\Controllers;
use Cyberbrains\Filemanager\UploadFileService;
use Exception;
use Illuminate\Http\Client\Request;

class FileController extends ApiController
{

    private UploadFileService $fileService;

    public function __construct()
    {
        $this->fileService = new UploadFileService();
    }

    public function upload(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $model = $this->fileService->upload($request->file('file'));
        } catch (Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
        return $this->sendResponse($model, 'File successfully uploaded');
    }
}