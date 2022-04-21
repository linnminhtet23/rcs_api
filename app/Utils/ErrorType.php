<?php


namespace App\Utils;


class ErrorType
{
    const SAVE_ERROR = ['type' => 'save error'];
    const UPDATE_ERROR = ['type' => 'update error'];
    const DELETE_ERROR = ['type' => 'delete error'];
    const FILE_UPLOAD_ERROR = ['type' => 'file upload error'];
    const FILE_DELETE_ERROR = ['type' => 'file delete error'];
    const RESOURCE_NOT_FOUND_ERROR = ['type' => 'resource not found error'];
}
