<?php

declare(strict_types=1);

namespace Example\Domain;

interface SendEmailRepository
{
    public function sendEmail(string $email): void;
}