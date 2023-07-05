<?php

declare(strict_types = 1);

namespace Travis\StreamingBackend\Infrastructures\Persistency\Doctrine\Repositories;

use Doctrine\ORM\NonUniqueResultException;
use Exception;
use Illuminate\Support\Facades\Hash;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Request;
use Travis\StreamingBackend\Domains\Entities\PasswordReset\PasswordResetRepositoryInterface;
use Travis\StreamingBackend\Domains\Entities\User\User;
use Travis\StreamingBackend\Domains\Entities\User\UserRepositoryInterface;
use Travis\StreamingBackend\Domains\ValueObjects\ResetPasswordVO;
use Slim\Exception\HttpException;
use Travis\StreamingBackend\Exceptions\UnauthorizedException;
use TypeError;
use Doctrine\ORM\EntityRepository;

class UserRepository implements UserRepositoryInterface
{
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManager $entity_manager,
    ) {
        $this->repository = $this->entity_manager->getRepository(User::class);
    }

    public function find(int $id): ?User
    {
        /* @var User $user*/
        $user = $this->repository->find($id);
        return $user;
    }

    public function findByUuid(string $uuid): ?User
    {
        /* @var User $user*/
        $user = $this->repository->findOneBy(["uuid" => $uuid]);
        return $user;
    }

    public function findByEmail(string $email): ?User
    {
        /* @var User $user*/
        $user = $this->repository->findOneBy(["email" => $email]);
        return $user;
    }

    public function save(User $user): void
    {
        $this->entity_manager->persist($user);
        $this->entity_manager->flush();
    }
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        // ensure the clientEntity is actually ours
        $user = $this->repository->findOneBy(['email' => $username]);
        if ($user instanceof User && password_verify($password, $user->getPassword())) {
            return $user;
        }

        throw new UnauthorizedException();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findOneByOrCnd(array $conditions): ?User
    {
        $query = $this->repository->createQueryBuilder('u');

        foreach ($conditions as $col => $value) {
            $query->orWhere($query->expr()->eq("u.$col", ":$col"));
            $query->setParameter($col, $value);
        }

        return $query
            ->getQuery()
            ->getOneOrNullResult();
    }
}
