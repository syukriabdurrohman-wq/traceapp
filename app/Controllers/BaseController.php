<?php

namespace App\Controllers;

use App\Services\AuthService;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 * class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $helpers = ['form', 'url', 'text'];

    protected AuthService $authService;

    protected \CodeIgniter\Session\Session $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session     = service('session');
        $this->authService = service('authService');
    }

    protected function page(string $view, array $data = []): string
    {
        return view($view, array_merge($this->buildBaseViewData(), $data));
    }

    protected function buildBaseViewData(): array
    {
        $currentUser = $this->authService->currentUser();

        return [
            'currentUser'       => $currentUser,
            'isAuthenticated'   => $currentUser !== null,
            'bottomNavigation'  => $this->getBottomNavigation($currentUser),
            'currentUriPath'    => trim((string) current_url(true)->getPath(), '/'),
            'appName'           => trace_app_name(),
            'appTagline'        => trace_app_tagline(),
            'appBrand'          => trace_app_brand(),
        ];
    }

    protected function getBottomNavigation(?array $currentUser): array
    {
        if ($currentUser === null) {
            return [];
        }

        $items = [
            ['label' => 'Home', 'icon' => 'house', 'href' => base_url('/')],
            ['label' => 'Laporan', 'icon' => 'document', 'href' => base_url('reports/create')],
        ];

        if (in_array($currentUser['role_code'], ['Admin', 'Manager', 'Supervisor'], true)) {
            $items[] = [
                'label' => $currentUser['role_code'] === 'Supervisor' ? 'Record' : 'Monitor',
                'icon'  => 'chart',
                'href'  => base_url('admin/reports')
            ];
        }

        if ($currentUser['role_code'] === 'Manager') {
            $items[] = ['label' => 'Trend', 'icon' => 'analytics', 'href' => base_url('manager')];
        }

        if ($currentUser['role_code'] === 'Admin') {
            $items[] = ['label' => 'Admin', 'icon' => 'shield', 'href' => base_url('admin/users')];
        }

        $items[] = ['label' => 'Profil', 'icon' => 'user', 'href' => base_url('profile')];

        return $items;
    }
}