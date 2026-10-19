<?php

namespace App\Libraries;

class SimpleCaptcha
{
    private const KEY = 'captcha_answer';

    /** Creates a question, remembers the answer in the session, returns the question text. */
    public function generate(): string
    {
        $a = random_int(1, 9);
        $b = random_int(1, 9);

        session()->set(self::KEY, $a + $b);

        return "{$a} + {$b}";
    }

    /** Checks the answer. Each question can be answered only once. */
    public function check(?string $input): bool
    {
        $expected = session()->get(self::KEY);
        session()->remove(self::KEY);   // one-time use

        $input = trim((string) $input);

        return $expected !== null && ctype_digit($input) && (int) $input === (int) $expected;
    }
}