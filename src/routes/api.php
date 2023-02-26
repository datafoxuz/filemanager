<?php

use Cyberbrains\Filemanager\Controllers\FileController;
use Illuminate\Routing\Route;

Route::post("filemanager/upload", [FileController::class, 'upload']);