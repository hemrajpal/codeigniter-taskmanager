<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function __construct(){
        $this->userModel = model(UserModel::class);
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'email'    => 'required|valid_email',
                'password' => 'required',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()
                    ->withInput()
                    ->with('errors', $this->validator->getErrors());
            }

            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if (!$user || !password_verify($password, $user->password)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid email or password.');
            }

            // Check email verification // do it later for email verification
            /* if (empty($user->email_verified_at)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Please verify your email before logging in.');
            } */

            // Check admin approval
            if ($user->status !== 'active') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Your account is awaiting admin approval.');
            }

            session()->regenerate();

            session()->set([
                'user_id'   => $user->id,
                'user_name' => $user->name,
                'user_role' => $user->role,
                'isLoggedIn' => true,
            ]);

            if ($user->role === 'admin') {
                return redirect()->to('/admin/dashboard');
            }

            return redirect()->to('/member/dashboard');
        }

        return view('auth/login');
    }

    public function signup()
    {
        return view('auth/signup');
    }

    public function register()
    {
        if (! $this->validate('register')) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        // Only grab validated data (safer than getPost())
        $data = $this->validator->getValidated();    

        $data = [
            'name'              => trim($data['name']),
            'email'             => strtolower(trim($data['email'])),
            'password'          => password_hash(
                $data['password'],
                PASSWORD_DEFAULT
            ),
            'role'              => 'member',
            //'status'            => 'pending', // do it later for email verification
            'status'            => 'active', 
            'email_verified_at' => null,
        ];

        $userId = $this->userModel->insert($data);

        if (! $userId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to create your account. Please try again.');
        }        

        /* $token = $tokenService->issue((int) $userId, \App\Helpers\TokenType::EmailVerification);

        $accountMailer = new \App\Libraries\AccountMailer();
        $sent = $accountMailer->sendVerification(trim($data['name']), strtolower(trim($data['email'])), $token); */

        return redirect()->to('/login')
            ->with(
                'success',
                //'Registration successful. Please verify your email and wait for admin approval.' // do it later for email verification
                'Registration successful.'
            );
    }

    public function verifyEmail(string $token)
    {
        $tokenService = new \App\Libraries\TokenService();

        $userId = $tokenService->consume($token, \App\Helpers\TokenType::EmailVerification);

        if ($userId === null) {
            return redirect()->to('/login')
                ->with('error', 'This verification link is invalid or has expired.');
        }

        $this->userModel->update($userId, [
            'email_verified_at' => date('Y-m-d H:i:s'),
            'status' => 'active',
        ]);

        return redirect()->to('/login')
            ->with('success', 'Email verified. An admin will review your account shortly.');
    }

    public function logout()
    {
        $session = session();

        // Clear only the login data, then issue a fresh session ID
        $session->remove(['isLoggedIn', 'user_id', 'user_role', 'user_name']);
        $session->regenerate(true);

        return redirect()->to('/login')->with('success', 'You have been logged out.');
    }
}
