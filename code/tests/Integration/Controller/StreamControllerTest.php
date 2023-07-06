<?php
namespace Test\Integration\Controller;

use Doctrine\ORM\EntityManager;
use Dotenv\Dotenv;
use http\Message\Body;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Psr7\Header;
use Slim\Psr7\Headers;
use Slim\Psr7\Request;
use Slim\Psr7\Response;
use Slim\Psr7\Uri;
use Travis\StreamingBackend\Controllers\AuthController;
use Travis\StreamingBackend\Controllers\StreamController;
use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\Stream\StreamRepositoryInterface;
use Travis\StreamingBackend\Infrastructures\Services\AuthService;
use Travis\StreamingBackend\Infrastructures\Services\StreamService;

class StreamControllerTest extends TestCase
{
    private App $app;
    private AuthService $auth_service;
    private StreamService $stream_service;
    private StreamRepositoryInterface $stream_repository;
    private Stream $stream;

    public function __construct()
    {
        parent::__construct();
        $app = require __DIR__.'/../../../bootstrap/app.php';
        $this->app = $app();
        $this->auth_service = $this->app->getContainer()->get(AuthService::class);
        $this->stream_service = $this->app->getContainer()->get(StreamService::class);
        $this->stream_repository = $this->app->getContainer()->get(StreamRepositoryInterface::class);
        $container = $this->app->getContainer();
        exec("./vendor/bin/doctrine-migrations migrations:migrate first --db-configuration=migrations-db-test.php --no-interaction --quiet");
        exec("./vendor/bin/doctrine-migrations migrations:migrate --db-configuration=migrations-db-test.php --no-interaction --quiet");
        /* @var EntityManager $entity_manager */
        $entity_manager = $container->get(EntityManager::class);
        $sql = "INSERT INTO oauth_clients (identifier, name, client_id, client_secret, redirect_uri, provider, created_at, updated_at) VALUES ('app', 'app', '123', '123', null, null, '2023-07-05 18:24:01', '2023-07-05 18:24:05');" ;
        $query = $entity_manager->getConnection()->prepare($sql);
        $query->executeQuery();
    }

    public function testCreateStream(): void
    {
        $userVO = new \Travis\StreamingBackend\Domains\ValueObjects\UserVO(
            email: 'test@test.com', password: '123456', name: 'test');
        $user = $this->auth_service->register(userVO: $userVO);
        /* @var AuthController $authController */
        $authController = $this->app->getContainer()->get(AuthController::class);
        $request = $this->createMock(Request::class);
        $request->method('getParsedBody')->willReturn([
            'username' => 'test@test.com',
            'password' => '123456',
            'grant_type' => 'password',
            'client_id' => '123',
            'scope' => []
        ]);
        $response = new Response();
        $response = $authController->login(
            $request,
            $response,
            []
        );
        $response = json_decode((string) $response->getBody());
        $access_token = $response->access_token;
        /* @var StreamController $streamController */
        $streamController = $this->app->getContainer()->get(StreamController::class);
        $request = $this->createMock(Request::class);
        $request->method('getParsedBody')->willReturn([
            'title' => 'test',
        ]);
        $request->method('hasHeader')->willReturn(true);
        $request->method('getHeader')->willReturn(['Bearer '.$access_token]);
        $request->method('getAttribute')->with('oauth_user_id')->willReturn($user->getId());
        $response = new Response();
        $result = $streamController->startStream(
            $request,
            $response,
            []
        );
        $result = json_decode((string) $result->getBody());
        $stream = $this->stream_repository->findUserActiveStream($user);
        $this->assertEquals($stream->getUuid(), $result->uuid);
    }
}