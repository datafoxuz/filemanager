<?php

use Cyberbrains\Filemanager\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::post("filemanager/upload", [FileController::class, 'upload']);