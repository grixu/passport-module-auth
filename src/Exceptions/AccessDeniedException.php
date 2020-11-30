<?php

namespace Grixu\PassportModuleAuth\Exceptions;

use Exception;

/**
 * Class AccessDeniedException
 * @package Grixu\PassportModuleAuth\Exceptions
 */
class AccessDeniedException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => 'Access denied',
            ],
            403
        );
    }
}
