<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\UserModel;

class AdminUserController extends BaseController
{
    public function users()
    {
        $search = $this->request->getGet('search');

        $userModel = new UserModel();

        if (!empty($search)) {
            $userModel->groupStart()->like('name', $search)->orLike('email', $search)->groupEnd();
        }
        
        $users = $userModel->orderBy('id', 'DESC')->paginate(10);

        return view('admin/user/users', [
            'users' => $users,
            'pager' => $userModel->pager,
            'search' => $search,
        ]);
    }

    public function editUser(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found.');
        }

        return view('admin/user/edit_user', [
            'user' => $user,
        ]);
    }

    public function updateUser(int $id)
    {
        $userModel = new \App\Models\UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found.');
        }

        $isSelf = (int) session()->get('user_id') === $id;

        $rules = [
            'name'  => 'required|min_length[2]|max_length[100]',
            'email' => "required|valid_email|max_length[255]|is_unique[users.email,id,{$id}]",
        ];

        if (! $isSelf) {
            $rules['role'] = 'required|in_list[admin,member]';
            $rules['status'] = 'required|in_list[pending,approved,rejected]';
        }

        $rules['email_verification'] = 'required|in_list[verified,unverified]';

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $email = trim((string) $this->request->getPost('email'));

        $data = [
            'name'  => $name,
            'email' => $email,
            'email_verified_at' =>
                $this->request->getPost('email_verification') === 'verified'
                    ? ($user->email_verified_at ?? date('Y-m-d H:i:s'))
                    : null,
        ];

        // Prevent changing your own role or account status.
        if (! $isSelf) {
            $data['role'] = $this->request->getPost('role');
            $data['status'] = $this->request->getPost('status');
        }

        if (! $userModel->update($id, $data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Unable to update user.');
        }

        return redirect()->to('/admin/users')
            ->with('success', 'User updated successfully.');
    }

    public function updateUserStatus(int $id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found.');
        }

        $status = $this->request->getPost('status');

        if (! in_array($status, ['pending', 'active', 'rejected'], true)) {
            return redirect()->to('/admin/users')
                ->with('error', 'Invalid user status.');
        }

        if (! $userModel->update($id, ['status' => $status])) {
            return redirect()->to('/admin/users')
                ->with('error', 'Unable to update user status.');
        }

        return redirect()->to('/admin/users')
            ->with('success', 'User status updated successfully.');
    }

    public function deleteUser(int $id)
    {
        $userModel = new UserModel();

        // Prevent the logged-in admin from deleting their own account.
        if ((int) session()->get('user_id') === $id) {
            return redirect()->to('/admin/users')
                ->with('error', 'You cannot delete your own account.');
        }

        $user = $userModel->find($id);

        if (! $user) {
            return redirect()->to('/admin/users')
                ->with('error', 'User not found.');
        }

        if (! $userModel->delete($id)) {
            return redirect()->to('/admin/users')
                ->with('error', 'Unable to delete user.');
        }

        return redirect()->to('/admin/users')
            ->with('success', 'User deleted successfully.');
    }
}
