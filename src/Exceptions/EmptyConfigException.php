<?php

namespace Grixu\PassportModuleAuth\Exceptions;

use Exception;

/**
 * Class EmptyConfigException
 * @package Grixu\PassportModuleAuth\Exceptions
 */
class EmptyConfigException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => 'Empty configuration for PassportModuleAuth',
            ],
            500
        );
    }
}
