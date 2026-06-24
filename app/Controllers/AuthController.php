<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function loginPage(): string
    {
        $this->response
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0');

        return $this->page('Auth/LoginPage', [
            'pageTitle' => 'Login',
            'pageClass' => 'AuthPage',
        ]);
    }

    public function login()
    {
        $payload = $this->request->getPost(['phone', 'password']);
        $rules   = config(\Config\Validation::class)->authLogin;

        if (! service('validation')->setRules($rules)->run($payload)) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }

        // Langsung bypass verifikasi tanpa OTP untuk login
        $success = $this->authService->attempt((string) $payload['phone'], (string) $payload['password'], $this->request);

        if (! $success) {
            return redirect()->back()->withInput()->with('errors', ['login' => 'Nomor HP atau password tidak valid.']);
        }

        return redirect()->to(base_url('/'))->with('success', 'Login berhasil.');
    }

    public function otpPage(): string
    {
        return $this->page('Auth/LoginPage', [
            'pageTitle' => 'Verifikasi OTP',
            'pageClass' => 'AuthPage',
            'otpMode'   => true,
        ]);
    }

    public function verifyOtp()
    {
        $otp = trim((string) $this->request->getPost('otp'));
        if ($otp === '') {
            return redirect()->back()->withInput()->with('errors', ['otp' => 'OTP wajib diisi.']);
        }

        $result = $this->authService->verifyOtpLogin($otp, $this->request);
        if (! $result['success']) {
            return redirect()->back()->withInput()->with('errors', $result['errors']);
        }

        return redirect()->to(base_url('/'))->with('success', 'Login berhasil.');
    }

    public function registerPage(): string
    {
        $this->response
            ->setHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0');

        return $this->page('Auth/RegisterPage', [
            'pageTitle' => 'Register',
            'pageClass' => 'AuthPage',
        ]);
    }

    public function register()
    {
        $result = $this->authService->register($this->request->getPost([
            'fullName',
            'email',
            'username',
            'phone',
            'password',
        ]));

        if (! $result['success']) {
            return redirect()->back()->withInput()->with('errors', $result['errors']);
        }

        return redirect()->to(base_url('login'))->with('success', 'Registrasi berhasil. Silakan login.');
    }

    public function logout()
    {
        $this->authService->logout();

        return redirect()->to(base_url('login'));
    }
}