<?php

namespace App\Services;

use App\Models\RefreshTokenModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService
{
    public function __construct(
        private readonly RefreshTokenModel $refreshTokenModel = new RefreshTokenModel(),
    ) {
    }

    public function issuePair(array $user): array
    {
        $issuedAt      = time();
        $accessExpiry  = $issuedAt + ((int) env('app.jwtAccessTtl', 3600));
        $refreshExpiry = $issuedAt + ((int) env('app.jwtRefreshTtl', 1209600));

        $basePayload = [
            'iss'  => env('app.jwtIssuer', base_url('/')),
            'aud'  => env('app.jwtAudience', base_url('/')),
            'sub'  => (int) $user['id'],
            'name' => $user['full_name'],
            'role' => $user['role_code'],
            'iat'  => $issuedAt,
        ];

        $accessToken = JWT::encode(array_merge($basePayload, [
            'exp'  => $accessExpiry,
            'type' => 'access',
        ]), $this->accessSecret(), 'HS256');

        $refreshToken = JWT::encode(array_merge($basePayload, [
            'exp'  => $refreshExpiry,
            'type' => 'refresh',
            'jti'  => bin2hex(random_bytes(16)),
        ]), $this->refreshSecret(), 'HS256');

        $this->refreshTokenModel->insert([
            'user_id'    => $user['id'],
            'token_hash' => hash('sha256', $refreshToken),
            'expires_at' => date('Y-m-d H:i:s', $refreshExpiry),
        ]);

        return [
            'accessToken'      => $accessToken,
            'accessTokenTtl'   => $accessExpiry,
            'refreshToken'     => $refreshToken,
            'refreshTokenTtl'  => $refreshExpiry,
        ];
    }

    public function refreshPair(string $refreshToken, array $user): array
    {
        $payload = $this->verifyRefreshToken($refreshToken);
        $record  = $this->refreshTokenModel
            ->where('token_hash', hash('sha256', $refreshToken))
            ->where('revoked_at', null)
            ->first();

        if ($record === null || (int) $record['user_id'] !== (int) $user['id']) {
            throw new \RuntimeException('Refresh token tidak ditemukan.');
        }

        if (strtotime((string) $record['expires_at']) < time()) {
            throw new \RuntimeException('Refresh token sudah kedaluwarsa.');
        }

        $this->refreshTokenModel->update($record['id'], ['revoked_at' => date('Y-m-d H:i:s')]);

        return $this->issuePair(array_merge($user, ['id' => (int) $payload->sub]));
    }

    public function verifyAccessToken(string $token): object
    {
        return JWT::decode($token, new Key($this->accessSecret(), 'HS256'));
    }

    public function verifyRefreshToken(string $token): object
    {
        return JWT::decode($token, new Key($this->refreshSecret(), 'HS256'));
    }

    public function getBearerToken($request): ?string
    {
        $header = $request->getHeaderLine('Authorization');

        if (! str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return trim(substr($header, 7));
    }

    private function accessSecret(): string
    {
        return (string) env('app.jwtSecret', 'ChangeThisJwtSecretBeforeProduction');
    }

    private function refreshSecret(): string
    {
        return (string) env('app.jwtRefreshSecret', 'ChangeThisRefreshSecretBeforeProduction');
    }
}
