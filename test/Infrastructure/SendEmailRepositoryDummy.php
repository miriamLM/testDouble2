<?php

declare(strict_types=1);

namespace ExampleTest\Infrastructure;

use Example\Domain\SendEmailRepository;

final class SendEmailRepositoryDummy implements SendEmailRepository
{
    public function sendEmail(string $email): void
    {

    }
}