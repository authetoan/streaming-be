<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories;

use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use Travis\StreamingBackend\Domains\Entities\Client\Client;
use Travis\StreamingBackend\Domains\Entities\Permission\Permission;
use Travis\StreamingBackend\Domains\Entities\Permission\PermissionRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\User;
use TypeError;
use Doctrine\ORM\EntityRepository;

class PermissionRepository implements ScopeRepositoryInterface, PermissionRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManager $entity_manager,
    ) {
        $this->repository = $this->entity_manager->getRepository(Permission::class);
    }

    public function find(int $id): ?Permission
    {
        /* @var Permission $permission*/
        $permission = $this->repository->find($id);
        return $permission;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function save(Permission $permission): void
    {
        $this->entity_manager->persist($permission);
        $this->entity_manager->flush();
    }

    public function getScopeEntityByIdentifier($identifier): ScopeEntityInterface
    {
        /* @var Permission $permission */
        $permission = $this->repository->findOneBy(['name' => $identifier]);
        if ($permission === null) {
            throw OAuthServerException::invalidScope($identifier);
        }
        return $permission;
    }

    public function finalizeScopes(
        array $scopes,
        $grantType,
        ClientEntityInterface $clientEntity,
        $userIdentifier = null
    ): array {
        if ($userIdentifier !== null) {
            /* @var User $user */
            $user = $this->entity_manager->getRepository(User::class)->find($userIdentifier);
            if ($user === null) {
                throw new TypeError('User not found');
            }
            $user_permissions = $user->getCombinePermission();
            $this->filterScopes($scopes, $user_permissions);

            return array_values($scopes);
        }
        /* @var Client $clientEntity */
        $permissions = $clientEntity->getPermissions();
        $this->filterScopes($scopes, $permissions->toArray());

        return array_values($scopes);
    }

    /**
     * @return void
     */
    private function filterScopes(array &$request_scopes, array $actual_scopes)
    {
        if (empty($request_scopes) || $request_scopes[0] == '*') {
            $request_scopes = $actual_scopes;

            return;
        }

        $formatted_actual_scopes = array_column(
            json_decode(json_encode($actual_scopes), true),
            'name'
        );

        foreach ($request_scopes as $key => $scope) {
            if (!in_array($scope->getName(), $formatted_actual_scopes)) {
                unset($request_scopes[$key]);
            }
        }
    }

    public function findByName(string $name): ?Permission
    {
        /* @var Permission $permission*/
        $permission = $this->repository->findOneBy(['name' => $name]);

        return $permission;
    }
}
