<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class JwtAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $tokenService = service('tokenService');
        $token        = $tokenService->getBearerToken($request);

        if ($token === null) {
            return service('response')->setJSON([
                'status'  => 'error',
                'message' => 'Bearer token wajib dikirim.',
            ])->setStatusCode(401);
        }

        try {
            $tokenService->verifyAccessToken($token);
        } catch (\Throwable $exception) {
            return service('response')->setJSON([
                'status'  => 'error',
                'message' => 'Token tidak valid atau sudah kedaluwarsa.',
            ])->setStatusCode(401);
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
