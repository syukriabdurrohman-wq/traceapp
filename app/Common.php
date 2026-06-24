<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

if (! function_exists('trace_app_name')) {
    function trace_app_name(): string
    {
        return 'TRACE';
    }
}

if (! function_exists('trace_app_tagline')) {
    function trace_app_tagline(): string
    {
        return 'Tracking Report & Activity Control Engine';
    }
}

if (! function_exists('trace_app_brand')) {
    function trace_app_brand(): string
    {
        return trace_app_name() . ' — ' . trace_app_tagline();
    }
}

if (! function_exists('trace_logo_path')) {
    function trace_logo_path(): string
    {
        return FCPATH . 'Assets/Image/logo.png';
    }
}

if (! function_exists('trace_logo_url')) {
    function trace_logo_url(): string
    {
        return base_url('Assets/Image/logo.png');
    }
}

if (! function_exists('trace_user_initial')) {
    function trace_user_initial(?array $user): string
    {
        return strtoupper(substr((string) ($user['full_name'] ?? 'U'), 0, 1));
    }
}

if (! function_exists('trace_user_photo_url')) {
    function trace_user_photo_url(?array $user): ?string
    {
        $path = trim((string) ($user['profile_photo_path'] ?? ''));

        if ($path === '') {
            return null;
        }

        return base_url($path);
    }
}

if (! function_exists('trace_icon')) {
    function trace_icon(string $name): string
    {
        return match ($name) {
            'logout' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M15 8l4 4-4 4"/><path d="M19 12H9"/><path d="M13 4H6a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h7"/></svg>',
            'profile' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.8-3.4 5-5 8-5s6.2 1.6 8 5"/></svg>',
            'home', 'house' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10.5V20h13V10.5"/></svg>',
            'document' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3.5h6l4 4V20H8z"/><path d="M14 3.5V8h4"/></svg>',
            'pin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s6-5.4 6-11a6 6 0 1 0-12 0c0 5.6 6 11 6 11Z"/><circle cx="12" cy="10" r="2.5"/></svg>',
            'camera' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 8h3l1.5-2h7L17 8h3v10H4z"/><circle cx="12" cy="13" r="3.2"/></svg>',
            'team' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="9" r="3"/><circle cx="17" cy="10" r="2.5"/><path d="M4.5 19c1.2-2.8 3.4-4.2 6-4.2 2.5 0 4.7 1.4 5.8 4.2"/><path d="M15 16.5c.9-.9 2-1.4 3.4-1.4 1.5 0 2.8.7 3.6 2"/></svg>',
            'clipboard' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="6" y="5" width="12" height="16" rx="2"/><path d="M9 5.5h6"/><path d="M9 11h6"/><path d="M9 15h4"/></svg>',
            'truck' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h11v8H3z"/><path d="M14 10h3l3 3v2h-6z"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/></svg>',
            'box' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5 12 4l8 3.5"/><path d="M4 7.5V16l8 4 8-4V7.5"/><path d="M12 20V11"/></svg>',
            'alert' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4 3 20h18L12 4Z"/><path d="M12 9v4"/><circle cx="12" cy="16.5" r="1"/></svg>',
            'user' => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="4"/><path d="M4 20c1.8-3.4 5-5 8-5s6.2 1.6 8 5"/></svg>',
            'chart' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 18V9"/><path d="M12 18V5"/><path d="M19 18v-7"/><path d="M3.5 20h17"/></svg>',
            'shield' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 5 6v5c0 4.6 2.9 8.9 7 10 4.1-1.1 7-5.4 7-10V6l-7-3Z"/><path d="m9.5 12 1.8 1.8L14.8 10"/></svg>',
            'analytics' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 19h16"/><path d="M7 16V9"/><path d="M12 16V5"/><path d="M17 16v-4"/></svg>',
            'edit' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 20 4.5-1 9.7-9.7a2.1 2.1 0 0 0-3-3L5.5 16 4 20Z"/><path d="m13.5 6.5 4 4"/></svg>',
            'detail' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.6-6 9.5-6 9.5 6 9.5 6-3.6 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.7"/></svg>',
            'pdf' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 3.5h6l4 4V20H8z"/><path d="M14 3.5V8h4"/><path d="M10 14h4"/><path d="M10 17h4"/></svg>',
            'toggle' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="8" width="18" height="8" rx="4"/><circle cx="15.5" cy="12" r="2.5"/></svg>',
            'copy' => '<svg viewBox="0 0 24 24" aria-hidden="true"><rect x="9" y="9" width="10" height="10" rx="2"/><path d="M6 15H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v1"/></svg>',
            'install' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4v10"/><path d="m8.5 10.5 3.5 3.5 3.5-3.5"/><path d="M5 19h14"/></svg>',
            'close' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 6 12 12"/><path d="M18 6 6 18"/></svg>',
            'next' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>',
            'back' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m15 6-6 6 6 6"/></svg>',
            default => '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/></svg>',
        };
    }
}
