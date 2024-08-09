<?php

class Evaluation extends Model
{

    public  $evaluationId;
    public  $customerId;
    public  $installSat     = null;
    public  $anyDetails     = null;
    public  $conMeasured    = null;
    public  $prepSat        = null;
    public  $workSat        = null;
    public  $answers        = null;
    public  $friendlyHelpfull = null;
    public  $remarks        = null;
    public  $nameSigned     = null;
    public  $dateSend       = null;
    public  $dateSigned     = null;
    public  $loginHash      = null;
    public  $digitalSigned  = 0;    
    public  $grade = 0;

    public  $created;
    public  $modified;

    /**
     * @var \Customer
     */
    protected static $customer;

    private $properties = [];

    public function __set($name, $value) {
        $this->properties[$name] = $value;
    }

    public function __get($name) {
        return $this->properties[$name] ?? null;
    }

    /**
     * validate object
     */

    public function validate()
    {
        if (!is_numeric($this->customerId)) {
            $this->setPropInvalid('customerId');
        }
        if (!is_numeric($this->digitalSigned)) {
            $this->setPropInvalid('digitalSigned');
        }
        


    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {

        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            if (!$this->digitalSigned) {
                return true;
            }
        }
        return false;
    }


    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {

        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin()) {
            
            return true;
        }
        return false;
    }

    
    /**
     * @return \Customer
     */
    public function getCustomer()
    {
        if (!static::$customer) {
            static::$customer = CustomerManager::getCustomerById($this->customerId);
        }

        return static::$customer;
    }

    /*
    * GetPdf
    */

    public function getPdf()
    {

        require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

        $oMPDF = new \Mpdf\Mpdf([
        'tempDir' => sys_get_temp_dir(),
        'mode' => 'utf-8',
        'format' => 'A4']); // A4-L
        
        
        $oCustomer = $this->getCustomer();
        $oEvaluation = $this;
       
        $oMPDF->shrink_tables_to_fit = 0;       
        $oMPDF->curlAllowUnsafeSslRequests = true;

        define("DOMPDF_ENABLE_REMOTE", false);
        $sStylesheet                 = file_get_contents(DOCUMENT_ROOT . getAdminCss('pdf', 'devices'));
        $oMPDF->WriteHTML($sStylesheet, 1);
 
        ob_start();
        include_once getAdminSnippet('pdfBody_evaluation', 'customers');
        $sHtml = ob_get_contents();
        ob_end_clean();

        if (http_get('html') == 1) {
        echo $sHtml;
        die;
        }

        $oMPDF->WriteHTML($sHtml);
    //echo $sHtml; die;
    

        return $oMPDF;
    }

    /**
     * 
     */
    public function send()
    {
        $oCustomer = $this->getCustomer();
        $sTo = $oCustomer->contactPersonEmail;
        $sFrom = 'info@optivolt.nl';

        $oTemplate = TemplateManager::getTemplateByName('evaluation_request', Locales::language());

        // check if template exists
        if (empty($oTemplate)) {
            Debug::logError('', 'Template does not exists: `evaluation_request` (evaluation_request)', __FILE__, __LINE__, '', Debug::LOG_IN_EMAIL);
        } else {

            $aReplace["[customer_loginLink]"] = CLIENT_HTTP_URL . "/evaluation/" . $this->loginHash;
            $oTemplate->replaceVariables($oCustomer, $aReplace);            
            $sSubject  = $oTemplate->getSubject();
            $sMailBody = $oTemplate->getTemplate();
            MailManager::sendMail($sTo, $sSubject, $sMailBody, $sFrom);

            $this->dateSend = date('Y-m-d', time());
            EvaluationManager::saveEvaluation($this);

        }
    }

}