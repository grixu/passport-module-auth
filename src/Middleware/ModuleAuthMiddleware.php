<?php

namespace Grixu\PassportModuleAuth\Middleware;

use Closure;
use Exception;
use Grixu\PassportModuleAuth\Exceptions\AccessDeniedException;
use Grixu\PassportModuleAuth\Exceptions\ClientNotFoundException;
use Grixu\PassportModuleAuth\Exceptions\EmptyConfigException;
use Grixu\PassportModuleAuth\Exceptions\NotValidModuleException;
use Grixu\PassportModuleAuth\Models\ClientModule;
use Illuminate\Http\Request;
use Laravel\Passport\Token;
use Lcobucci\JWT\Configuration;

/**
 * Class ModuleAuthMiddleware
 * @package Grixu\PassportModuleAuth
 */
class ModuleAuthMiddleware
{
    public function handle(Request $request, Closure $next, $module)
    {
        if (empty(config('passport-module-auth.modules'))) {
            throw new EmptyConfigException();
        }

        if (!in_array($module, config('passport-module-auth.modules'))) {
            throw new NotValidModuleException();
        }

        $bearerToken = $request->bearerToken();
        $parser = Configuration::forUnsecuredSigner()->parser();
        $tokenId = $parser->parse($bearerToken)->claims()->get('jti');

        try {
            $client = Token::findOrFail($tokenId)->client_id;
        } catch (Exception $exception) {
            throw new ClientNotFoundException();
        }

        try {
            ClientModule::query()
                ->where(
                    [
                        ['client_id', '=', $client],
                        ['module', '=', $module]
                    ]
                )
                ->firstOrFail();
        } catch (Exception $exception) {
            throw new AccessDeniedException();
        }

        return $next($request);
    }
}
