<?php

namespace App\Models;

use CodeIgniter\Model;

class VerificationTokenModel extends Model
{
    protected $table         = 'verification_tokens';
    protected $returnType    = 'object';
    protected $allowedFields = ['user_id', 'type', 'token_hash', 'expires_at', 'used_at'];
    protected $useTimestamps = true;
    protected $updatedField  = '';
}