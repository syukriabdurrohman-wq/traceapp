<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $currentUser = service('authService')->currentUser();

        if ($currentUser === null) {
            return redirect()->to(base_url('login'))->with('error');
        }

        $allowedRoles = is_array($arguments) ? $arguments : [];

        if (! in_array($currentUser['role_code'], $allowedRoles, true)) {
            return redirect()->to(base_url('/'))->with('error', 'Anda tidak memiliki izin untuk halaman tersebut.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
