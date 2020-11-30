<?php

namespace Grixu\PassportModuleAuth\Exceptions;

use Exception;

/**
 * Class ClientNotFoundException
 * @package Grixu\PassportModuleAuth\Exceptions
 */
class ClientNotFoundException extends Exception
{
    public function render()
    {
        return response()->json(
            [
                'message' => 'Client not found.',
            ],
            403
        );
    }
}
