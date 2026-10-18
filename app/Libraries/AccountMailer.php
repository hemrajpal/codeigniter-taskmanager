<?php

namespace App\Libraries;

class AccountMailer
{
    public function sendVerification(string $name, string $email, string $token): bool
    {
        $mail = service('email');

        $mail->setTo($email);
        $mail->setSubject('Verify your email address');
        $mail->setMessage(view('emails/verify_email', [
            'name' => $name,
            'url'  => site_url('verify-email/' . $token),
        ]));

        if (! $mail->send(false)) {
            log_message('error', 'Verification email failed for {email}: {debug}', [
                'email' => $email,
                'debug' => $mail->printDebugger(['headers']),
            ]);

            return false;
        }

        return true;
    }
}