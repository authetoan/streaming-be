<?php

declare(strict_types = 1);

use Defuse\Crypto\Key;
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\XmlDriver;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface as LeagueUserRepositoryInterface;
use League\OAuth2\Server\ResourceServer;
use Noodlehaus\Config;
use Psr\Container\ContainerInterface;
use Travis\StreamingBackend\Controllers\AuthController;
use Travis\StreamingBackend\Domains\Entities\AccessToken\AccessTokenRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\Client\ClientRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\Stream\StreamRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\UserRepositoryInterface;
use Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories\ClientRepository;
use Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories\PermissionRepository;
use Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories\StreamRepository;
use Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories\UserRepository;
use Travis\StreamingBackend\Infrastructures\Persistency\Redis\Repositories\AccessTokenRepository;
use Travis\StreamingBackend\Infrastructures\Persistency\Redis\Repositories\RefreshTokenRepository;
use Travis\StreamingBackend\Infrastructures\Services\AuthService;
use Travis\StreamingBackend\Infrastructures\Services\ExternalStreamingService\ExternalStreamingServiceInterface;
use Travis\StreamingBackend\Infrastructures\Services\League\AuthorizationServer;
use Travis\StreamingBackend\Infrastructures\Services\League\BearerTokenResponse;
use Travis\StreamingBackend\Infrastructures\Services\StreamService;

return static function (): ContainerInterface {

    $container = new Container();

    //Config Doctrine
    $container->set(EntityManager::class, function () {
        $config_connection = new Config(__DIR__.'/../config/doctrine.php');
        $config = new Configuration();
        $mapping_path = $config_connection->get('doctrine.mapping_path');
        $config->setMetadataDriverImpl(new XmlDriver($mapping_path));
        $connection = DriverManager::getConnection([
            'host' => $config_connection->get('doctrine.host'),
            'driver'   => $config_connection->get('doctrine.driver'),
            'user'     => $config_connection->get('doctrine.user'),
            'password' => $config_connection->get('doctrine.password'),
            'dbname'   => $config_connection->get('doctrine.dbname'),
        ], $config);
        $config->setProxyDir(__DIR__.'/../storage/Proxies');
        $config->setProxyNamespace('Travis\StreamingBackend\Proxies');
        return new EntityManager($connection, $config);
    });

    //Config Redis

    $container->set(Redis::class, function () {
        $config_connection = new Config(__DIR__.'/../config/redis.php');
        $redis = new Redis();
        $redis->connect($config_connection->get('redis.host'), $config_connection->get('redis.port'));
        return $redis;
    });

    //Register Repositories
    $container->set(ScopeRepositoryInterface::class, function () use ($container) {
        return new PermissionRepository(
            entity_manager: $container->get(EntityManager::class)
        );
    });

    $container->set(LeagueUserRepositoryInterface::class, function () use ($container) {
        return new UserRepository(
            entity_manager: $container->get(EntityManager::class)
        );
    });

    $container->set(ClientRepositoryInterface::class, function () use ($container) {
        return new ClientRepository(
            entity_manager: $container->get(EntityManager::class)
        );
    });

    $container->set(AccessTokenRepositoryInterface::class, function () use ($container) {
        return new AccessTokenRepository(
            redis: $container->get(Redis::class)
        );
    });

    $container->set(UserRepositoryInterface::class, function () use ($container) {
        return new UserRepository(
            entity_manager: $container->get(EntityManager::class)
        );
    });

    $container->set(StreamRepositoryInterface::class, function () use ($container) {
        return new StreamRepository(
            entity_manager: $container->get(EntityManager::class)
        );
    });

    //Register Oauth2 Server

    $container->set(ResourceServer::class, function () use ($container) {
        $config_oauth2 = new Config(__DIR__.'/../config/oauth2.php');

        return new ResourceServer(
            accessTokenRepository:$container->get(AccessTokenRepositoryInterface::class),
            publicKey: $config_oauth2->get('oauth2.public_key'),
        );
    });


    $container->set(AuthorizationServer::class, function () use ($container) {
        $config_oauth2 = new Config(__DIR__.'/../config/oauth2.php');
        return new AuthorizationServer(
            clientRepository: $container->get(ClientRepositoryInterface::class),
            accessTokenRepository: $container->get(AccessTokenRepositoryInterface::class),
            scopeRepository: $container->get(ScopeRepositoryInterface::class),
            privateKey: $config_oauth2->get('oauth2.private_key'),
            encryptionKey: Key::loadFromAsciiSafeString($config_oauth2->get('oauth2.encrypt_key')),
            responseType: new BearerTokenResponse()
        );
    });

    $container->set(AuthService::class, function () use ($container) {
        return new AuthService(user_repository: $container->get(UserRepositoryInterface::class));
    });

    $container->set(AuthController::class, function () use ($container) {
        return new AuthController(
            $container->get(AuthorizationServer::class),
            $container->get(UserRepositoryInterface::class),
            $container->get(RefreshTokenRepository::class),
            $container->get(AuthService::class),
            $container->get(ResourceServer::class)
        );
    });

    //Register other service
    $container->set(ExternalStreamingServiceInterface::class, function () use ($container) {
        $config_streaming = new Config(__DIR__.'/../config/streaming.php');
        $class = $config_streaming->get('streaming.driver');
        return new $class();
    });

    $container->set(StreamService::class, function () use ($container) {
        return new StreamService(
            stream_repository: $container->get(StreamRepositoryInterface::class),
            externalStreamingService: $container->get(ExternalStreamingServiceInterface::class)
        );
    });

    return $container;
};
