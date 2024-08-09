<?php

class Inventarisation extends Model
{

    

    public  $inventarisationId;
    public  $parentInventarisationId;
    public  $loggerId       = null;
    public  $userId         = null;
    public  $customerId     = null;
    public  $customerName   = null;
    public  $name;
    public  $kva;
    public  $position;
    public  $freeFieldAmp    = null;
    public  $stroomTrafo     = null;
    public  $control;
    public  $type;
    public  $relaisNr;
    public  $engineKw;
    public  $turningHours;
    public  $photoNrs;
    public  $trafoNr;
    public  $mlProposed;
    public  $remarks;
   
    public  $created;
    public  $modified;

    private $aSubInventarisations;
    
 

    /**
     * validate object
     */

    public function validate()
    {
        
        
    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        ///if (UserManager::getCurrentUser()->isEngineer() || UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
            return true;
       // }
       // return false;
    }

    public function isReadOnly() {
        if (UserManager::getCurrentUser()->isEngineer() || UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
            return false;
        }
         return true;

    }

    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        if (UserManager::getCurrentUser()->isEngineer() || UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isClientAdmin()) {
            return true;
        }
        return false;
    }

    /**
     * check if item is online (except with preview mode)
     *
     * @param bool $bPreviewMode
     *
     * @return bool
     */
    public function isOnline($bPreviewMode = false)
    {

        return true;
    }

 
    public function getSubInventarisations()
    {
        $this->aSubInventarisations = InventarisationManager::getSubInventarisations($this->inventarisationId);
        return $this->aSubInventarisations;
    }
    

    /*
     * Get customer
     * @return Customer
     */
    public function getCustomer()
    {
        return CustomerManager::getCustomerById($this->customerId);
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
        'format' => 'A4-L']); // A4-L

        $oCustomer = CustomerManager::getCustomerById($this->customerId);
        $oUser = UserManager::getUserById($this->userId);
        $oInventarisation = $this;
        $aInventarisations = $oInventarisation->getSubInventarisations(); 

        $oMPDF->shrink_tables_to_fit = 0;
        //$oMPDF->setAutoTopMargin     = 'stretch';
        //$oMPDF->margin-top = 10mmzz;
        $oMPDF->curlAllowUnsafeSslRequests = true;

        define("DOMPDF_ENABLE_REMOTE", false);
        $sStylesheet                 = file_get_contents(DOCUMENT_ROOT . getAdminCss('pdf', 'inventarisations'));
        $oMPDF->WriteHTML($sStylesheet, 1);
 
        ob_start();
        include_once getAdminSnippet('pdfBody', 'inventarisations');
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


}