<?php

/*
|--------------------------------------------------------------------------
| Vercel entry point (demo / staging only)
|--------------------------------------------------------------------------
| Vercel runs PHP as short-lived serverless functions with a read-only file
| system, so everything Laravel writes goes to /tmp and the SQLite database
| is rebuilt from the seeders when a fresh instance starts.
|
| That makes this a review copy of the site: anything changed in the CRM is
| lost when Vercel recycles the instance. Use real PHP hosting for production.
|
| Optional Vercel environment variables:
|   APP_KEY         a fixed key (php artisan key:generate --show) so logins
|                   survive across instances
|   ADMIN_PASSWORD  CRM password; without it the CRM login stays locked
*/

$tmp = '/tmp/aurco';

$env = static function (string $key, string $value): void {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
};
$default = static function (string $key, string $value) use ($env): void {
    if (getenv($key) === false || getenv($key) === '') {
        $env($key, $value);
    }
};

$default('APP_ENV', 'production');
$default('APP_DEBUG', 'false');
$default('APP_URL', 'https://'.($_SERVER['HTTP_X_FORWARDED_HOST'] ?? $_SERVER['HTTP_HOST'] ?? 'localhost'));
// Must stay the same across requests, or sessions (and so forms and logins) break.
// Set APP_KEY in Vercel for a key nobody else can derive.
$default('APP_KEY', 'base64:'.base64_encode(hash('sha256', 'aurco-demo|'.(getenv('VERCEL_DEPLOYMENT_ID') ?: getenv('VERCEL_GIT_COMMIT_SHA') ?: getenv('VERCEL_URL') ?: ($_SERVER['HTTP_HOST'] ?? 'local')), true)));
$default('ADMIN_PASSWORD', bin2hex(random_bytes(24)));
$default('LOG_CHANNEL', 'stderr');
$default('SESSION_DRIVER', 'file');
$default('CACHE_STORE', 'file');
$default('DB_CONNECTION', 'sqlite');
$default('DB_DATABASE', "$tmp/database.sqlite");
$default('VIEW_COMPILED_PATH', "$tmp/storage/framework/views");

// Laravel's own caches normally live in bootstrap/cache, which is read-only here.
foreach (['PACKAGES', 'SERVICES', 'CONFIG', 'ROUTES', 'EVENTS'] as $cache) {
    $default("APP_{$cache}_CACHE", "$tmp/cache/".strtolower($cache).'.php');
}

foreach (['cache', 'storage/app/public', 'storage/framework/cache/data', 'storage/framework/sessions', 'storage/framework/views', 'storage/logs'] as $dir) {
    if (! is_dir("$tmp/$dir")) {
        mkdir("$tmp/$dir", 0755, true);
    }
}

require __DIR__.'/../vendor/autoload.php';

$boot = static function () use ($tmp) {
    $app = require __DIR__.'/../bootstrap/app.php';
    $app->useStoragePath("$tmp/storage");

    return $app;
};

// First request on a fresh instance: build the database from the seeders.
$database = getenv('DB_DATABASE');
if (! file_exists($database)) {
    touch($database);
    $console = $boot()->make(Illuminate\Contracts\Console\Kernel::class);
    $console->call('migrate', ['--force' => true]);
    $console->call('db:seed', ['--force' => true]);
}

$boot()->handleRequest(Illuminate\Http\Request::capture());
