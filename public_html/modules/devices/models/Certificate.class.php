<?php

class Certificate extends Model
{

    public  $certificateId;
    public  $deviceId;
    public  $userId;
        
    public  $vbbNr;
    public  $testInstrument;
    public  $testSerialNr;
    public  $nextcheck;
    public  $visualCheck;    
    public  $weerstandBeLeRPE;    
    public  $isolatieWeRISO;    
    public  $lekstroomIEA;    
    public  $lekstroomTouch;    
    public  $created;
    public  $modified;
    

    /**
     * validate object
     */

    public function validate()
    {
      
        if (empty($this->deviceId)) {
            $this->setPropInvalid('deviceId');
        }
        if (!is_numeric($this->userId)) {
            $this->setPropInvalid('userId');
        }
        if (empty($this->visualCheck)) {
            $this->setPropInvalid('visualCheck');
        }
       
    }

    /**
     * check if item is editable
     *
     * @return Boolean
     */
    public function isEditable()
    {
        return true;
    }

    /**
     * check if item is deletable
     *
     * @return Boolean
     */
    public function isDeletable()
    {
        return true;
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
        
        $oUser = UserManager::getUserById($this->userId);
        $oCertificate = $this;
       
        $oMPDF->shrink_tables_to_fit = 0;       
        $oMPDF->curlAllowUnsafeSslRequests = true;

        define("DOMPDF_ENABLE_REMOTE", false);
        $sStylesheet                 = file_get_contents(DOCUMENT_ROOT . getAdminCss('pdf', 'devices'));
        $oMPDF->WriteHTML($sStylesheet, 1);
 
        ob_start();
        include_once getAdminSnippet('pdfBody', 'devices');
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
