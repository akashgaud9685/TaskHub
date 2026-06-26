<?php

define('SUPABASE_URL', getenv('SUPABASE_URL') ?: 'https://fmigjebieplnswqpgbgv.supabase.co');
define('SUPABASE_ANON_KEY', getenv('SUPABASE_ANON_KEY') ?: 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImZtaWdqZWJpZXBsbnN3cXBnYmd2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3ODIwNTc4OTQsImV4cCI6MjA5NzYzMzg5NH0.paRSi8SFT0HDYCMiCyBqvqYiQKlzNGMfyWn2Fj2h_Fw');
define('SUPABASE_SERVICE_KEY', getenv('SUPABASE_SERVICE_KEY') ?: '');

error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Set session save path to writable directory (for InfinityFree compatibility)
$sessionPath = __DIR__ . '/../storage/sessions';
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);

function getDB()
{
    static $pdo = null;

    if ($pdo === null) {
        require_once __DIR__ . '/../lib/SupabasePDO.php';
        $key = SUPABASE_SERVICE_KEY ?: SUPABASE_ANON_KEY;
        $pdo = new SupabasePDO(SUPABASE_URL . '/rest/v1/', $key);
    }

    return $pdo;
}

function getBizDB()
{
    return getDB();
}
