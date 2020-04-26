<?php

declare(strict_types=1);

namespace ExampleTest\Application;

use Example\Application\CreateUser;
use Example\Domain\Exceptions\InvalidUsernameException;
use Example\Domain\{User, UserNameValidator};
use ExampleTest\Infrastructure\{UsernameRepositoryDummy, UsernameRepositoryStub, SendEmailRepositoryDummy, SendEmailValidSpy};
use PHPUnit\Framework\TestCase;
use Example\Domain\UserRepository;

final class CreateUserTest extends TestCase
{
    /**
     * @test
     */
    public function shouldCreateUser()
    {
        $userRepository = new UsernameRepositoryStub();
        $usernameValidator = new UserNameValidator();
        $sendEmailRepository = new SendEmailValidSpy();
        
        $createUser = new CreateUser($userRepository, $usernameValidator, $sendEmailRepository);

        $repositoryUser = \Mockery::mock(UserRepository::class);

        $expectedUser = new User('validUsername', '123456', 'email@prueba');
        

        $repositoryUser
            ->shouldReceive('create')
            ->once()
            ->with($expectedUser)
            ->andReturnNull();

        $repositoryUser
            ->shouldReceive('findUser')
            ->once()
            ->with($expectedUser->username())
            ->andReturnNull();

        $actualUser = $createUser('validUsername', '123456', 'email@prueba');

        $this->assertTrue( $sendEmailRepository->sendEmailWasCalled() );
        $this->assertEquals($expectedUser, $actualUser);
    }

    public function testShouldThrowExceptionWhenUsernameIsInvalid()
    {
        $this->expectException(InvalidUsernameException::class);
        $userRepository = new UsernameRepositoryDummy();
        $usernameValidator = new UserNameValidator();
        $sendEmailRepository = new SendEmailRepositoryDummy();

        $createUser = new CreateUser($userRepository, $usernameValidator, $sendEmailRepository);

        $createUser('#invalidUsername','123456', 'email@prueba');
    }

}