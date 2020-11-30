<?php

namespace Grixu\PassportModuleAuth\Tests;

use Closure;
use Grixu\PassportModuleAuth\Exceptions\AccessDeniedException;
use Grixu\PassportModuleAuth\Exceptions\ClientNotFoundException;
use Grixu\PassportModuleAuth\Exceptions\EmptyConfigException;
use Grixu\PassportModuleAuth\Exceptions\NotValidModuleException;
use Grixu\PassportModuleAuth\Middleware\ModuleAuthMiddleware;
use Grixu\PassportModuleAuth\Models\ClientModule;
use Grixu\PassportModuleAuth\Tests\Helpers\BaseTestCase;
use Illuminate\Http\Request;
use Laravel\Passport\Token;

/**
 * Class ModuleAuthMiddlewareTest
 * @package Grixu\PassportModuleAuth\Tests
 */
class ModuleAuthMiddlewareTest extends BaseTestCase
{
    protected $request;
    protected $closure;
    protected $obj;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = new Request();

        $bearerToken = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiOTE1MjQ4ZjkwNTg4MjNiYTMwNWQyYjI3N2RjYTcxYWFjNzliZGRkOGY4MjMwNzkwZDY2NzNlY2ExOGJiNDU3ODU5ZGI0N2ZmNTRhYjAwZWMiLCJpYXQiOjE2MDYzODY1NTEsIm5iZiI6MTYwNjM4NjU1MSwiZXhwIjoxNjM3OTIyNTUxLCJzdWIiOiIiLCJzY29wZXMiOlsiKiJdfQ.hms3w-kEuh_LmszxCh38SRggIxl-mq5t3Jp33S48_J6RTg6rXuTM2sQ_bjq3I6g4wv0SmvJnd5FBvUQKnkcKd9bm5GGwEN8kf7eb_Djii1zQT7W3S-T5HQ8BETqjM2f-WUNyx7JjAdsOCwSkE6al3Zc84gJUJp4pGity9BnwBQMShaImWzblLTg_ZYLNrML8G2erwB1Bya6-j4qUjL5jv2tYEEsVhDTv66fcBA6B4HRpDS-bbX9EPtO629BksrKpQvCqzhKN9zWLuw2kIrd_PBs0QGjRmuky0A4qsP6UTRfcQBiniqEGosNcRuBaND6dMQfJwO2HSRusZa7d0ooZNCpfmc-RxViqYSzDfMojA44ARXNypjt6j4MMf_6-HZAcqiAoWgaJzPd_IxlqQkaVC3LRoTLKlK_TtV7CJDD-1F4H-opZNLbJOdf5pe82vJeIzSGA6dCyVflcRXiEQ_hJ7Xpabn5bUc9wXeLQUrquGCB9fKbcvogEn8tI4OPsc3sDMIGS1JF3sz8WA9ELTij9c6lm4WBFRQN29A5H4eTcW9aOjjpnVKMXp93vcrTk9WIRPzOvLy4wp_S91I5IYcFTTvRbE-y9t_NJdS1Wr4fGsqakJIsT9Irs002pAUfhvh3KviS14cqVH4V6BYZwMdpRYGmUEOX3KatU-kJa71VkUMM';
        $this->request->headers->add(['Authorization' => 'Bearer '.$bearerToken]);

        $this->closure = Closure::fromCallable(function ($request) { return $request; });

        Token::create(
            [
                'id' => '915248f9058823ba305d2b277dca71aac79bddd8f8230790d6673eca18bb457859db47ff54ab00ec',
                'client_id' => 2,
                'scopes' => '["*"]',
                'revoked' => 0,
            ]
        );

        ClientModule::create(
            [
                'client_id' => 2,
                'module' => 'product'
            ]
        );

        $this->obj = new ModuleAuthMiddleware();
    }

    /** @test */
    public function normal_pass()
    {
        $result = $this->obj->handle($this->request, $this->closure, 'product');

        $this->assertEquals(Request::class, get_class($result));
    }

    /** @test */
    public function wrong_module()
    {
        try {
            $this->obj->handle($this->request, $this->closure, 'something');

            $this->assertTrue(false);
        } catch (AccessDeniedException $e) {
            $this->assertTrue(false);
        } catch (ClientNotFoundException $e) {
            $this->assertTrue(false);
        } catch (EmptyConfigException $e) {
            $this->assertTrue(false);
        } catch (NotValidModuleException $e) {
            $this->assertTrue(true);
        }
    }

    /** @test */
    public function client_not_exists()
    {
        Token::query()->delete();

        try {
            $this->obj->handle($this->request, $this->closure, 'product');

            $this->assertTrue(false);
        } catch (AccessDeniedException $e) {
            $this->assertTrue(false);
        } catch (ClientNotFoundException $e) {
            $this->assertTrue(true);
        } catch (EmptyConfigException $e) {
            $this->assertTrue(false);
        } catch (NotValidModuleException $e) {
            $this->assertTrue(false);
        }
    }

    /** @test */
    public function authorization_not_exists()
    {
        ClientModule::query()->delete();

        try {
            $this->obj->handle($this->request, $this->closure, 'product');

            $this->assertTrue(false);
        } catch (AccessDeniedException $e) {
            $this->assertTrue(true);
        } catch (ClientNotFoundException $e) {
            $this->assertTrue(false);
        } catch (EmptyConfigException $e) {
            $this->assertTrue(false);
        } catch (NotValidModuleException $e) {
            $this->assertTrue(false);
        }
    }
}
