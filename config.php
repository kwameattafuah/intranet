<?php
/*
 * Environment configuration — update these values for each deployment.
 * Do NOT commit real credentials to a public repo.
 */

// ── Base URL (no trailing slash) ───────────────────────────────────────────
// On cPanel set this to e.g. https://yourdomain.com or https://yourdomain.com/intranet
define('APP_URL',   getenv('APP_URL')   ?: 'http://10.112.2.50:70');

// ── Database ───────────────────────────────────────────────────────────────
define('DB_HOST',   getenv('DB_HOST')   ?: '127.0.0.1');
define('DB_PORT',   getenv('DB_PORT')   ?: '3306');
define('DB_USER',   getenv('DB_USER')   ?: 'root');
define('DB_PASS',   getenv('DB_PASS')   ?: 'Aa123456');
define('DB_NAME',   getenv('DB_NAME')   ?: 'gacl_db');

// ── Organisation name (shown in UI) ───────────────────────────────────────
define('ORG_NAME',  'Ghana Airports Company Limited');
define('ORG_SHORT', 'GACL');
