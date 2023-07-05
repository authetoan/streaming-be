<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories;

use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Travis\StreamingBackend\Domains\Entities\Client\Client;
use Travis\StreamingBackend\Domains\Entities\Client\ClientRepositoryInterface;
use Doctrine\ORM\EntityRepository;

class ClientRepository implements \Travis\StreamingBackend\Domains\Entities\Client\ClientRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private EntityManager $entity_manager,
    ) {
        $this->repository = $this->entity_manager->getRepository(Client::class);
    }

    public function find(int $id): ?Client
    {
        /* @var Client $client*/
        $client = $this->repository->find($id);
        return $client;
    }

    public function findByUuid(string $uuid): ?Client
    {
        /* @var Client $client*/
        $client = $this->repository->findOneBy(["uuid" => $uuid]);
        return $client;
    }

    public function save(Client $client): void
    {
        $this->entity_manager->persist($client);
        $this->entity_manager->flush();
    }

    public function getClientEntity($clientIdentifier): ?ClientEntityInterface
    {
        /** @var Client $client */
        $client = $this->repository->findOneBy(["client_id" => $clientIdentifier]);
        return $client;
    }

    /**
     * @return void
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        // TODO: Implement validateClient() method.
    }
}
