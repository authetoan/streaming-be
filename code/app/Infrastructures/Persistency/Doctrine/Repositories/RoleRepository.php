<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories;

use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\UserRepositoryInterface as LeagueUserRepositoryInterface;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Travis\StreamingBackend\Domains\Entities\Role\Role;
use Travis\StreamingBackend\Domains\Entities\User\User;
use TypeError;
use Doctrine\ORM\EntityRepository;

class RoleRepository
{
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManager $entity_manager,
    ) {
        $this->repository = $this->entity_manager->getRepository(Role::class);
    }

    public function find(int $id): ?Role
    {
        /* @var Role $role*/
        $role = $this->repository->find($id);
        return $role;
    }

    public function save(Role $role): void
    {
        $this->entity_manager->persist($role);
        $this->entity_manager->flush();
    }
}
