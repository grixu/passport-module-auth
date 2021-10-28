<?php

namespace Grixu\PassportModuleAuth\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ClientModule
 * @package Grixu\PassportModuleAuth
 */
class ClientModule extends Model
{
    public $timestamps = true;

    protected $table = 'client_modules';

    protected $casts = [
        'client_id' => 'integer',
        'module' => 'string',
    ];

    protected $fillable = [
        'client_id',
        'module',
    ];
}
