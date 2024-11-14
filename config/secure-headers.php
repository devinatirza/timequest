<?php

return [

    /*
     * Server
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Server
     *
     * Note: when server is empty string, it will not add to response header
     */
    'server' => '',

    /*
     * X-Content-Type-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Content-Type-Options
     *
     * Available Value: 'nosniff'
     */
    'x-content-type-options' => 'nosniff',

    /*
     * X-Download-Options
     *
     * Reference: https://msdn.microsoft.com/en-us/library/jj542450(v=vs.85).aspx
     *
     * Available Value: 'noopen'
     */
    'x-download-options' => 'noopen',

    /*
     * X-Frame-Options
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-Frame-Options
     *
     * Available Value: 'deny', 'sameorigin', 'allow-from <uri>'
     */
    'x-frame-options' => 'sameorigin',

    /*
     * X-Permitted-Cross-Domain-Policies
     *
     * Reference: https://www.adobe.com/devnet/adobe-media-server/articles/cross-domain-xml-for-streaming.html
     *
     * Available Value: 'all', 'none', 'master-only', 'by-content-type', 'by-ftp-filename'
     */
    'x-permitted-cross-domain-policies' => 'none',

    /*
     * X-Powered-By
     *
     * Note: it will not add to response header if the value is empty string.
     *
     * Also, verify that expose_php is turned Off in php.ini.
     */
    'x-powered-by' => '',

    /*
     * X-XSS-Protection
     *
     * Reference: https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/X-XSS-Protection
     *
     * Available Value: '1', '0', '1; mode=block'
     */
    'x-xss-protection' => '1; mode=block',

    /*
     * Referrer-Policy
     *
     * Available Value: 'no-referrer', 'no-referrer-when-downgrade', 'origin', 'origin-when-cross-origin',
     *                  'same-origin', 'strict-origin', 'strict-origin-when-cross-origin', 'unsafe-url'
     */
    'referrer-policy' => 'no-referrer',

    /*
     * Cross-Origin-Embedder-Policy
     */
    'cross-origin-embedder-policy' => 'unsafe-none',

    /*
     * Cross-Origin-Opener-Policy
     */
    'cross-origin-opener-policy' => 'unsafe-none',

    /*
     * Cross-Origin-Resource-Policy
     */
    'cross-origin-resource-policy' => 'cross-origin',

    /*
     * Clear-Site-Data
     */
    'clear-site-data' => [
        'enable' => false,
        'all' => false,
        'cache' => true,
        'cookies' => true,
        'storage' => true,
        'executionContexts' => true,
    ],

    /*
     * HTTP Strict Transport Security
     */
    'hsts' => [
        'enable' => false,
        'max-age' => 31536000,
        'include-sub-domains' => false,
        'preload' => false,
    ],

    /*
     * Expect-CT
     */
    'expect-ct' => [
        'enable' => false,
        'max-age' => 2147483648,
        'enforce' => false,
        'report-uri' => null,
    ],

    /*
     * Permissions Policy
     */
    'permissions-policy' => [
        'enable' => true,

        'accelerometer' => ['self' => true],
        'autoplay' => ['self' => true],
        'camera' => ['self' => true],
        'fullscreen' => ['self' => true],
        'geolocation' => ['self' => true],
        'gyroscope' => ['self' => true],
        'magnetometer' => ['self' => true],
        'microphone' => ['self' => true],
        'payment' => ['self' => true],
        'picture-in-picture' => ['self' => true],
        'usb' => ['self' => true],
        'xr-spatial-tracking' => ['self' => true],
    ],

    /*
     * Content Security Policy
     */
    'csp' => [
        'enable' => true,
        'report-only' => false,
        'report-to' => '',
        'report-uri' => [],
        'block-all-mixed-content' => false,
        'upgrade-insecure-requests' => false,
        'base-uri' => [],
        'child-src' => [],
        'connect-src' => [],
        'default-src' => [],
        'font-src' => [],
        'form-action' => [],
        'frame-ancestors' => [],
        'frame-src' => [],
        'img-src' => [],
        'manifest-src' => [],
        'media-src' => [],
        'navigate-to' => [
            'unsafe-allow-redirects' => false,
        ],
        'object-src' => [],
        'plugin-types' => [],
        'prefetch-src' => [],
        'require-trusted-types-for' => [
            'script' => false,
        ],
        'sandbox' => [
            'enable' => false,
            'allow-downloads-without-user-activation' => false,
            'allow-forms' => false,
            'allow-modals' => false,
            'allow-orientation-lock' => false,
            'allow-pointer-lock' => false,
            'allow-popups' => false,
            'allow-popups-to-escape-sandbox' => false,
            'allow-presentation' => false,
            'allow-same-origin' => false,
            'allow-scripts' => false,
            'allow-storage-access-by-user-activation' => false,
            'allow-top-navigation' => false,
            'allow-top-navigation-by-user-activation' => false,
        ],
        'script-src' => [
            'none' => false,
            'self' => false,
            'report-sample' => false,
            'allow' => [],
            'schemes' => [],
            'unsafe-inline' => false,
            'unsafe-eval' => false,
            'unsafe-hashes' => false,
            'strict-dynamic' => false,
            'hashes' => [
                'sha256' => [],
                'sha384' => [],
                'sha512' => [],
            ],
        ],
        'script-src-attr' => [],
        'script-src-elem' => [],
        'style-src' => [],
        'style-src-attr' => [],
        'style-src-elem' => [],
        'trusted-types' => [
            'enable' => false,
            'allow-duplicates' => false,
            'default' => false,
            'policies' => [],
        ],
        'worker-src' => [],
    ],
];
