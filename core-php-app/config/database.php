<?php
/**
 * Database Configuration
 */

return [
    'host' => 'localhost',
    'dbname' => 'minofqcm_the_team_manager',
    'username' => 'minofqcm_team_manager',
    'password' => 'Abdur_310303',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
