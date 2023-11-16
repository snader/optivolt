<?php

class CspReportsController extends CoreController
{
    /**
     * @inheritdoc
     */
    public function index()
    {
        $this->end();
        $sRequest = Request::getRaw();
        if (!empty($sRequest)) {
            MailManager::sendMail(DEFAULT_ERROR_EMAIL, 'CSP Report', $sRequest);
        }
    }
}
