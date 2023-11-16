<?php

# check if there is a logged in user
if (http_get('logout')) {
    UserManager::logout(ADMIN_FOLDER . '/');
}

if (http_get('controller') != 'login') {
    $_SESSION['loginReferrer'] = getCurrentUrlPath();
}

if (!$oCurrentUser && http_get('controller') != 'login') {
    http_redirect(ADMIN_FOLDER . '/login');
}
