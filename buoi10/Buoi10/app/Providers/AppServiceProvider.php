<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();

        // Automatically detect XAMPP MySQL port (33066 or 3306) and database, or fallback to SQLite if MySQL is off
        $host = '127.0.0.1';
        $user = 'root';
        $pass = '';

        $connected = false;
        foreach ([33066, 3306] as $tryPort) {
            $fp = @fsockopen($host, $tryPort, $errno, $errstr, 0.2);
            if ($fp) {
                fclose($fp);
                try {
                    new \PDO("mysql:host={$host};port={$tryPort};dbname=buoi10_ecommerce", $user, $pass, [\PDO::ATTR_TIMEOUT => 1]);
                    config([
                        'database.default' => 'mysql',
                        'database.connections.mysql.host' => $host,
                        'database.connections.mysql.port' => $tryPort,
                        'database.connections.mysql.database' => 'buoi10_ecommerce',
                        'database.connections.mysql.username' => $user,
                        'database.connections.mysql.password' => $pass,
                    ]);
                    DB::purge('mysql');
                    $connected = true;
                    break;
                } catch (\Throwable $e) {
                    // Continue
                }
            }
        }

        if (!$connected && file_exists(database_path('database.sqlite'))) {
            config(['database.default' => 'sqlite']);
            DB::purge('sqlite');
        }
    }
}
