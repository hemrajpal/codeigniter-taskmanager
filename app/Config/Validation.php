<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Validation\StrictRules\CreditCardRules;
use CodeIgniter\Validation\StrictRules\FileRules;
use CodeIgniter\Validation\StrictRules\FormatRules;
use CodeIgniter\Validation\StrictRules\Rules;

class Validation extends BaseConfig
{
    // --------------------------------------------------------------------
    // Setup
    // --------------------------------------------------------------------

    /**
     * Stores the classes that contain the
     * rules that are available.
     *
     * @var list<string>
     */
    public array $ruleSets = [
        Rules::class,
        FormatRules::class,
        FileRules::class,
        CreditCardRules::class,
    ];

    /**
     * Specifies the views that are used to display the
     * errors.
     *
     * @var array<string, string>
     */
    public array $templates = [
        'list'   => 'CodeIgniter\Validation\Views\list',
        'single' => 'CodeIgniter\Validation\Views\single',
    ];

    // --------------------------------------------------------------------
    // Rules
    // --------------------------------------------------------------------

    // Named rule group
    public array $register = [
        'name' => [
            'rules' => 'required|min_length[2]|max_length[100]',
            'errors' => [
                'required' => 'Name is required.',
                'min_length' => 'Name must be at least 2 characters.',
            ],
        ],

        'email' => [
            'rules' => 'required|valid_email|max_length[150]|is_unique[users.email]',
            'errors' => [
                'required' => 'Email is required.',
                'valid_email' => 'Please enter a valid email.',
                'is_unique' => 'This email is already registered.',
            ],
        ],

        'password' => [
            'rules' => 'required|min_length[8]',
            'errors' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must contain at least 8 characters.',
            ],
        ],

        'password_confirm' => [
            'rules' => 'required|matches[password]',
            'errors' => [
                'required' => 'Please confirm your password.',
                'matches' => 'Passwords do not match.',
            ],
        ],
    ];
}
