<?php


namespace ExampleTest\Infrastructure;

use Example\Domain\User;
use Example\Domain\UserRepository;

final class UserRepositoryInMemory implements UserRepository
{
    /** @var User[] */
    private array $list = [];

    public function __construct(string $username, string $password,string $email)
    {
        $this->list[$username]= new User($username, $password, $email);
    }

    public function create(User $user): void
    {
        $this->list[$user->username()] = $user;
    }

    public function findUser(string $username): ?User
    {
        return $this->list[$username] ?? null;
    }
}