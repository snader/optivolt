<?php

class WebhookController extends CoreController
{

    /**
     * @inheritdoc
     */
    protected static $allowed_actions = [
        'deploy',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        // regardless of action, do not render
        $this->end();
    }

    /**
     * @inheritdoc
     */
    public function index()
    {
        // no body
    }

    /**
     * delegate deploy functions
     * 
     */
    public function deploy()
    {
        # check if call is valid
        if (isset($_SERVER['REMOTE_ADDR']) && in_array($_SERVER['REMOTE_ADDR'], LANDGOEDVOORN_WHITELIST_IPS)) {
            if (Request::param('ID') == 'pre') {
                $this->pre();
            } elseif (Request::param('ID') == 'post') {
                $this->post();
            }
        } else {
            return Router::httpError('404');
        }
    }

    /**
     * Perform 'Pre' actions
     *
     */
    protected function pre()
    {
        # set maintenance message
        if (Settings::exists('maintenance')) {
            $oMaintenanceSetting        = SettingManager::getSettingByName('maintenance');
            $oMaintenanceSetting->value = 1;
            SettingManager::saveSetting($oMaintenanceSetting);
        }
    }

    /**
     * Perform 'Post' actions
     *
     */
    protected function post()
    {
        # clear all cache
        Cache::flushAll();

        # set maintenance message
        if (Settings::exists('maintenance')) {
            $oMaintenanceSetting        = SettingManager::getSettingByName('maintenance');
            $oMaintenanceSetting->value = 0;
            SettingManager::saveSetting($oMaintenanceSetting);
        }
    }
}
