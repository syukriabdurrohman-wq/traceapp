<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\TokenService;

class AuthTokenController extends BaseController
{
    public function __construct(
        private readonly TokenService $tokenService = new TokenService(),
    ) {
    }

    public function issueToken()
    {
        $payload = $this->request->getJSON(true);
        if (! is_array($payload)) {
            $payload = $this->request->getPost();
        }

        $user = $this->authService->verifyCredentials((string) ($payload['login'] ?? ''), (string) ($payload['password'] ?? ''));

        if ($user === null) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Kredensial tidak valid.',
            ])->setStatusCode(401);
        }

        $tokens = $this->tokenService->issuePair($user);

        return $this->response->setJSON([
            'status' => 'success',
            'data'   => [
                'user'   => [
                    'id'       => (int) $user['id'],
                    'name'     => $user['full_name'],
                    'email'    => $user['email'],
                    'username' => $user['username'],
                    'role'     => $user['role_code'],
                ],
                'tokens' => $tokens,
            ],
        ]);
    }

    public function refreshToken()
    {
        $payload = $this->request->getJSON(true);
        if (! is_array($payload)) {
            $payload = $this->request->getPost();
        }

        try {
            $refreshToken = (string) ($payload['refreshToken'] ?? '');
            $decoded      = $this->tokenService->verifyRefreshToken($refreshToken);
            $user         = (new \App\Models\UserModel())->findDetailedById((int) $decoded->sub);

            if ($user === null) {
                throw new \RuntimeException('User tidak ditemukan.');
            }

            $tokens = $this->tokenService->refreshPair($refreshToken, $user);

            return $this->response->setJSON([
                'status' => 'success',
                'data'   => ['tokens' => $tokens],
            ]);
        } catch (\Throwable $exception) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $exception->getMessage(),
            ])->setStatusCode(401);
        }
    }
}
