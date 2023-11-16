Redirect Module
Current version: v1.0.0
Created by: Robin van Blaricum

=========================

Module for managing redirect inside the CMS

=========================

You can either make redirect based on an old URL and a new URL, or make regular expressions.
Importing from Excel is also an option.
All redirect will be type 301.

Add this to your index below the config.inc inclusion, might make it dynamic in the future:




if (moduleExists('redirect')) {
    #Check if current URL is stored in Database for redirecting
#First run trough the specific redirects
#When matching, redirect to the new URL
#If not matching, run trough the regular expressions
    $oRedirect = RedirectManager::getRedirectByPatternAndType(getCurrentUrlPath(true, true), Redirect::TYPE_SPECIFIC);
    if ($oRedirect) {
        http_redirect($oRedirect->newUrl, false, true);
    } else {
        $aRedirects = RedirectManager::getRedirectsByFilter(array("type" => Redirect::TYPE_EXPRESSION));
        if (!empty($aRedirects)) {
            foreach ($aRedirects as $oRedirect) {
                $sUrl = getCurrentUrlPath(true, true);
                $sPattern = preg_replace('/#/', '\#', $oRedirect->pattern);
                if (preg_match('#' . $sPattern . '#i', $sUrl)) {
                    http_redirect(preg_replace('#' . $sPattern . '#i', $oRedirect->newUrl, $sUrl));
                }
            }
        }
    }
}