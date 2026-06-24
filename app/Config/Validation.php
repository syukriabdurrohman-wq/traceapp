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

    public array $authLogin = [
        'phone'    => 'required|min_length[5]|max_length[30]',
        'password' => 'required|min_length[8]|max_length[120]',
    ];

    public array $authRegister = [
        'fullName' => 'required|min_length[3]|max_length[120]',
        'email'    => 'permit_empty|valid_email|max_length[160]|is_unique[Users.email]',
        'username' => 'required|alpha_numeric_punct|min_length[4]|max_length[60]|is_unique[Users.username]',
        'phone'    => 'required|min_length[5]|max_length[30]|is_unique[Users.phone]',
        'password' => 'required|min_length[8]|max_length[120]',
    ];

    public array $dailyReport = [
        'reportDate'         => 'required|valid_date[Y-m-d]',
        'workerUserId'       => 'required|is_natural_no_zero',
        'currentLocation'    => 'required|min_length[5]|max_length[255]',
        'areaCode'           => 'required|in_list[AreaLanal,AreaSwangi,AreaRpi,AreaLaut,Lainnya]',
        'weatherCode'        => 'required|in_list[Cerah,Hujan,Mendung]',
        'realizationSummary' => 'permit_empty|max_length[5000]',
        'lightToolSummary'   => 'permit_empty|max_length[5000]',
        'materialSummary'    => 'required|min_length[5]|max_length[5000]',
        'obstacleShape'      => 'permit_empty|min_length[3]|max_length[255]',
        'obstacleCause'      => 'permit_empty|min_length[3]|max_length[255]',
        'obstacleImpact'     => 'permit_empty|min_length[3]|max_length[255]',
        'tomorrowPlan'       => 'permit_empty|min_length[5]|max_length[5000]',
        'overtimeEnabled'    => 'permit_empty|in_list[0,1]',
        'overtimeStart'      => 'permit_empty|regex_match[/^((([0-1][0-9]|2[0-3]):[0-5][0-9])|(24:00))$/]',
        'overtimeEnd'        => 'permit_empty|regex_match[/^((([0-1][0-9]|2[0-3]):[0-5][0-9])|(24:00))$/]',
    ];

    public array $adminUser = [
        'fullName' => 'required|min_length[3]|max_length[120]',
        'email'    => 'permit_empty|valid_email|max_length[160]',
        'username' => 'required|alpha_numeric_punct|min_length[4]|max_length[60]',
        'phone'    => 'required|min_length[5]|max_length[30]',
        'roleId'   => 'required|is_natural_no_zero',
        'status'   => 'required|in_list[Active,Inactive]',
        'password' => 'permit_empty|min_length[8]|max_length[120]',
    ];
}
