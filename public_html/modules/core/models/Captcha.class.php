<?php

class Captcha
{
    const verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    public  $websiteKey = '';
    public  $secretKey  = '';
    public  $uniqueId;
    private $html       = null;
    private $js         = null;

    /*
     * Construct a invisible captcha for a form
     * @param string $sSubmitClasses
     * @param string $sSubmitName
     * @param string $sSubmitValue
     */
    function __construct($sSubmitClasses = '', $sSubmitName = 'send', $sSubmitValue = 'Verzenden')
    {
        if (!Settings::get('reCaptchaWebsiteKey') || !Settings::get('reCaptchaSecretKey')) {
            die('De recaptcha settings zijn niet ingevuld');
        }

        $this->websiteKey = Settings::get('reCaptchaWebsiteKey');
        $this->secretKey  = Settings::get('reCaptchaSecretKey');
        $this->uniqueId   = uniqid();
        self::generateSubmit($sSubmitClasses, $sSubmitName, $sSubmitValue);
    }

    /*
     * Generate a submit button with the validate functions
     * 
     */
    public function generateSubmit($sSubmitClasses = '', $sSubmitName, $sSubmitValue)
    {
        $this->html = '';
        $this->html .= '<input type="hidden" class="do-validate" data-rule-required="true" name="hiddenRecaptcha" id="hiddenRecaptcha' . $this->uniqueId . '" title="' . SiteTranslations::get('captcha_error') . '" >' . PHP_EOL;
        $this->html .= '<button class="g-recaptcha ' . $sSubmitClasses . '" name="' . $sSubmitName . '" id="hiddenRecaptchaButton' . $this->uniqueId . '">' . $sSubmitValue . '</button>' . PHP_EOL;

        $this->js = '
                var recaptcha' . $this->uniqueId . ' = grecaptcha.render(document.getElementById("hiddenRecaptchaButton' . $this->uniqueId . '"), {
                    "sitekey" : "' . $this->websiteKey . '",
                    "callback" : sendFormRevalidate' . $this->uniqueId . ',
                    "size": "invisible"
                });

                function sendFormRevalidate' . $this->uniqueId . '(token) {
                    var googleResponse = grecaptcha.getResponse(recaptcha' . $this->uniqueId . ');
                    var currentForm = $("#hiddenRecaptchaButton' . $this->uniqueId . '").parents("form:first"); 
                        
                    $("#hiddenRecaptcha' . $this->uniqueId . '").val(googleResponse);
                        
                    if(currentForm.valid()){
                        currentForm.submit();
                    }else{
                        grecaptcha.reset(recaptcha' . $this->uniqueId . ');
                    }
                }
                ' . PHP_EOL;
    }

    /*
     * return html containing submit button
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /*
     * return js which must be added to the js bottom after jquery has loaded, but before the api loads.
     * @return string
     */
    public function getJs()
    {
        return $this->js;
    }

    /*
     * return true/false if response is valid
     * @param string $sResponse
     * @return boolean
     */
    public function validateBackend($sResponse)
    {
        $aFields = [
            'secret'   => $this->secretKey,
            'response' => $sResponse,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::verifyUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $aFields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $sResult = curl_exec($ch);
        curl_close($ch);

        $oJsonGoogleReply = json_decode($sResult);
        if (json_last_error() !== JSON_ERROR_NONE || !$oJsonGoogleReply->success) {
            return false;
        }

        return true;
    }
}

?>