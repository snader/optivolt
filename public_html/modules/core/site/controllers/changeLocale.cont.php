<?php

/*
 * Controller to handle the change of a language/locale
 */

// change to other language
if (is_numeric(http_get('localeId')) && $oLocale = LocaleManager::getLocaleById(http_get('localeId'))) {

    SiteTranslations::reset();
    http_redirect(getUrlProtocol() . $oLocale->getURLFormat());
    die;

} else {

    http_redirect('/');

}