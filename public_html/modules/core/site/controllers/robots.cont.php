<?php

// set plain text header
header('Content-type: text/plain');

$sSetting = Settings::get('robots');
// set special robots handling
if (ENVIRONMENT == 'production') {
    echo '# robots for ' . ENVIRONMENT . ' environment' . PHP_EOL;
    echo 'User-agent: *' . PHP_EOL;
    echo 'Disallow: /admin' . PHP_EOL;
    echo 'Disallow: /browserwarning' . PHP_EOL;
    echo 'Disallow: /cgi-bin' . PHP_EOL;
    echo 'Disallow: /errors' . PHP_EOL;
    echo 'Sitemap: ' . CLIENT_HTTP_URL . '/sitemap.xml' . PHP_EOL;
    echo $sSetting;
} else {
    echo '# robots for ' . ENVIRONMENT . ' environment' . PHP_EOL;
    echo 'User-agent: *' . PHP_EOL;
    echo 'Disallow: /' . PHP_EOL;
}