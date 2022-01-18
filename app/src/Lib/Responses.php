<?php

namespace App\Lib;

class Responses{
    const RESPONSE_SUCCESS = ['ResultCode' => 1, 'Result' => 'Success'];

    const RESPONSE_ERROR = ['ResultCode' => 0, 'Result' => 'Error'];

    const ERRORS = [
        'user_already_liked_post' => ['ErrorCode' => 'E1', 'ErrorDescription' => 'The user has already liked this post'],
        'user_already_liked_comment' => ['ErrorCode' => 'E2', 'ErrorDescription' => 'The user has already liked this comment'],
        'resource_not_exist' => ['ErrorCode' => 'E3', 'ErrorDescription' => 'The request resource does not exist'],
    ];
}
