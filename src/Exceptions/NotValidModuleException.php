<?php

namespace Grixu\PassportModuleAuth\Exceptions;

use Exception;

/**
 * Class NotValidModuleException
 * @package Grixu\PassportModuleAuth\Exceptions
 */
class NotValidModuleException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => 'Wrong module name in PassportModuleAuth',
            ],
            403
        );
    }
}
