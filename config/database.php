<?php

define('DB_CONNECTION', getenv('DB_CONNECTION') ?: 'pgsql');
define('DB_HOST', getenv('DB_HOST') ?: 'db.fmigjebieplnswqpgbgv.supabase.co');
define('DB_PORT', getenv('DB_PORT') ?: (DB_CONNECTION === 'pgsql' ? '5432' : '3306'));
define('DB_NAME', getenv('DB_NAME') ?: 'postgres');
define('DB_USER', getenv('DB_USER') ?: 'postgres');
define('DB_PASS', getenv('DB_PASS') ?: 'Akashgaud@7389#');

define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://fmigjebieplnswqpgbgv.supabase.co');
define('SUPABASE_ANON_KEY', getenv('SUPABASE_ANON_KEY') ?: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZtaWdqZWJpZXBsbnN3cXBnYmd2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODIwNTc4OTQsImV4cCI6MjA5NzYzMzg5NH0.paRSi8SFT0HDYCMiCyBqvqYiQKlzNGMfyWn2Fj2h_Fw');

function getDB()
{
    static $pdo = null;

    if ($pdo === null) {
        $driver = DB_CONNECTION;

        if ($driver === 'pgsql' && extension_loaded('pdo_pgsql')) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%s;dbname=%s',
                DB_HOST,
                DB_PORT,
                DB_NAME
            );
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } elseif ($driver === 'mysql') {
            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                DB_HOST,
                DB_PORT,
                DB_NAME
            );
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } else {
            // Fallback: Supabase REST API via cURL
            require_once __DIR__ . '/../lib/SupabasePDO.php';
            $pdo = new SupabasePDO(SUPABASE_URL . '/rest/v1/', SUPABASE_ANON_KEY);
        }
    }

    return $pdo;
}

function getBizDB()
{
    return getDB();
}
