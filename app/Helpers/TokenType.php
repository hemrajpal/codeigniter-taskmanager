<?php

namespace App\Helpers;

enum TokenType: string
{
    case EmailVerification = 'email_verification';
    case PasswordReset     = 'password_reset';
    case Invitation        = 'invitation';

    // Lifetime in hours per type
    public function ttlHours(): int
    {
        return match ($this) {
            self::EmailVerification => 24,
            self::PasswordReset     => 1,
            self::Invitation        => 168,
        };
    }
}