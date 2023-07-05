<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Travis\StreamingBackend\Domains\Entities\Stream\Stream;
use Travis\StreamingBackend\Domains\Entities\Stream\StreamRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\User;

class StreamRepository implements StreamRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManager $entity_manager,
    ) {
        $this->repository = $this->entity_manager->getRepository(Stream::class);
    }

    public function find(int $id): ?Stream
    {
        /* @var Stream $stream*/
        $stream = $this->repository->find($id);
        return $stream;
    }

    public function findByUuid(string $uuid): ?Stream
    {
        /* @var Stream $stream*/
        $stream = $this->repository->findOneBy(["uuid" => $uuid]);
        return $stream;
    }

    public function findAllActiveStream(int $limit, int $offset): array
    {
        /* @var Stream[] $streams*/
        $streams = $this->repository->findBy(criteria:["is_active" => true], limit: $limit, offset: $offset);
        return $streams;
    }

    public function findUserActiveStream(User $user): ?Stream
    {
        return $this->repository->findOneBy(["is_active" => true, "user" => $user]);
    }

    public function save(Stream $stream): void
    {
        $this->entity_manager->persist($stream);
        $this->entity_manager->flush();
    }
}
