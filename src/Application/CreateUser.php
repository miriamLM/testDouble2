<?php

declare(strict_types=1);

namespace Example\Application;

use Example\Domain\Exceptions\InvalidUserExistException;
use Example\Domain\User;
use Example\Domain\UserNameValidator;
use Example\Domain\UserRepository;

final class CreateUser
{
    private UserRepository $userRepository;
    private UserNameValidator $userNameValidator;

    public function __construct(UserRepository $userRepository, UserNameValidator $userNameValidator)
    {
        $this->userRepository = $userRepository;
        $this->userNameValidator = $userNameValidator;
    }

    public function __invoke(string $username, string $password, string $email): User
    {
        $this->userNameValidator->validate($username);

        if (null !== $this->userRepository->findUser($username)){
            throw new InvalidUserExistException();
        }
        
        $user = new User($username, $password, $email);
        $this->userRepository->create($user);
        return $user;
    }
}