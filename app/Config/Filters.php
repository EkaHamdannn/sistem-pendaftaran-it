<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\AuthFilter;

class Filters extends BaseFilters
{
    /**
     * Alias filter yang tersedia di aplikasi.
     * Kunci alias harus cocok dengan nama yang dipakai di Routes.php.
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // Filter autentikasi admin
        'auth'          => AuthFilter::class,
    ];

    /**
     * Filter global yang dijalankan sebelum dan sesudah setiap request.
     * CSRF diaktifkan secara global untuk semua request POST.
     */
    public array $globals = [
        'before' => [
            'csrf',
        ],
        'after' => [
            'toolbar',
        ],
    ];

    /**
     * Filter berdasarkan HTTP method.
     */
    public array $methods = [];

    /**
     * Filter berdasarkan pola URI.
     * Filter 'auth' sudah diterapkan via route group di Routes.php,
     * jadi tidak perlu lagi di sini.
     */
    public array $filters = [];
}
