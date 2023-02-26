<?php

use Cyberbrains\Filamanager\Controllers\FileController;
use Illuminate\Routing\Route;

Route::post("filemanager/upload", [FileController::class, 'upload']);