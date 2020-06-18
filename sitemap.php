<?php

define('DSP', DIRECTORY_SEPARATOR);
define('APP_ROOT', __DIR__.DSP);
define('CONFIG_PATH', APP_ROOT.DSP.'Config'.DSP);

include "bootstrap.php";

$config = array(
    'database'   => include(CONFIG_PATH.'database.php'),
    'routes'     => include(CONFIG_PATH.'routes.php'),
    'controller' => 'Controllers\\',
    'salt' => 'Борода не делает козла раввином'
);

$GLOBALS['database']['provider'] = \Classes\DBProvider::GetDriver($config['database'], $config['database']['provider']);

$report_model = new \Models\ReportModel();
$reports = $report_model->All();

header("Content-Type: application/xml;");

echo
'<?xml version="1.0" encoding="UTF-8"?>
<urlset
        xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9">
';

echo "<url><loc>https://cryptoscamalert.com</loc></url>";

if ($reports) {
    foreach ($reports as $key => $report) {
        echo "<url><loc>https://cryptoscamalert.com/report/$key</loc></url>";
    }
}

echo '</urlset>';