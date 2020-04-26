<?php

declare(strict_types=1);

namespace ExampleTest\Infrastructure;

use Example\Domain\SendEmailRepository;

class SendEmailValidSpy implements SendEmailRepository
{
    private $sendEmailWasCalled = false;

    public function sendEmail(string $email): void
    {
        $this->sendEmailWasCalled = true;
    }

    public function sendEmailWasCalled(): bool
    {
        return $this->sendEmailWasCalled;
    }
}
