<?php

declare(strict_types=1);

namespace ExampleTest\Application;

use Example\Application\CreateUser;
use Example\Domain\Exceptions\InvalidUsernameException;
use Mockery;
use Example\Domain\{Exceptions\InvalidUserExistException, User, UserNameValidator};
use ExampleTest\Infrastructure\{UsernameRepositoryDummy,
    SendEmailRepositoryDummy,
    SendEmailValidSpy,
    UserRepositoryInMemory};
use PHPUnit\Framework\TestCase;
use Example\Domain\UserRepository;

final class CreateUserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateUser()
    {
        $usernameValidator = new UserNameValidator();
        $sendEmailRepository = new SendEmailValidSpy();
        $repositoryUser = Mockery::mock(UserRepository::class);
        $createUser = new CreateUser($repositoryUser, $usernameValidator, $sendEmailRepository);


        $expectedUser = new User('validUsername', '123456', 'email@prueba');

        $repositoryUser
            ->shouldReceive('create')
            ->once()
            ->with(\Hamcrest\Matchers::equalTo($expectedUser))
            ->andReturnNull();

        $repositoryUser
            ->shouldReceive('findUser')
            ->once()
            ->with($expectedUser->username())
            ->andReturnNull();

        $actualUser = $createUser('validUsername', '123456', 'email@prueba');

        $this->assertEquals($expectedUser, $actualUser);

        $this->assertTrue($sendEmailRepository->sendEmailWasCalled());
    }

    public function testShouldThrowExceptionWhenUsernameIsInvalid()
    {
        $this->expectException(InvalidUsernameException::class);
        $userRepository = new UsernameRepositoryDummy();
        $usernameValidator = new UserNameValidator();
        $sendEmailRepository = new SendEmailRepositoryDummy();

        $createUser = new CreateUser($userRepository, $usernameValidator, $sendEmailRepository);

        $createUser('#invalidUsername', '123456', 'email@prueba');
    }

    public function testShouldThrowExceptionWhenUsernameExists()
    {
        $this->expectException(InvalidUserExistException::class);
        $usernameValidator = new UserNameValidator();
        $userRepository = new UserRepositoryInMemory('username','123456', 'email@prueba');
        $sendEmailRepository = new SendEmailRepositoryDummy();

        $createUser = new CreateUser($userRepository, $usernameValidator, $sendEmailRepository);
        $createUser('username', '123456', 'email@prueba');

    }

}