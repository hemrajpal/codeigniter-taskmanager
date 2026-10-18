<?php

namespace App\Libraries;

use App\Helpers\TokenType;
use App\Models\VerificationTokenModel;
use CodeIgniter\I18n\Time;

class TokenService
{
    private VerificationTokenModel $model;

    public function __construct()
    {
        $this->model = new VerificationTokenModel();
    }

    /** Creates a token and returns the RAW value (only the hash is stored). */
    public function issue(int $userId, TokenType $type): string
    {
        // One active token per user+type: invalidate older unused ones
        $this->model->where('user_id', $userId)
                    ->where('type', $type->value)
                    ->where('used_at', null)
                    ->delete();

        $raw = bin2hex(random_bytes(32));

        $this->model->insert([
            'user_id'    => $userId,
            'type'       => $type->value,
            'token_hash' => hash('sha256', $raw),
            'expires_at' => Time::now()->addHours($type->ttlHours())->toDateTimeString(),
        ]);

        return $raw;
    }

    /** Validates and consumes a token. Returns user_id, or null if invalid/expired/used. */
    public function consume(string $raw, TokenType $type): ?int
    {
        $row = $this->model
            ->where('token_hash', hash('sha256', $raw))
            ->where('type', $type->value)
            ->where('used_at', null)
            ->where('expires_at >=', Time::now()->toDateTimeString())
            ->first();

        if ($row === null) {
            return null;
        }

        $this->model->update($row->id, ['used_at' => Time::now()->toDateTimeString()]);

        return (int) $row->user_id;
    }
}