<?php

class Appointment extends Model
{

    public  $appointmentId;
    public  $userId;
    public  $customerId;
    public  $visitDate                  = null;
    public  $uitbreidingsmogelijkheden  = null;
    public  $uitbrInfo                  = null;
    public  $vLiner                     = null;

    public  $ml                         = null;
    public  $koperenRailen              = null;
    public  $PQkast                     = null;
    public  $onderhoudssticker          = null;
    public  $hoofdschakelaarTerug       = null;
    public  $finished                   = null;
    public  $mailed                     = null;
    public  $signature                  = null;
    public  $signatureName              = null;
    public  $modified;

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
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isEngineer()) {
           
                return true;
           
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
        if (UserManager::getCurrentUser()->isClientAdmin() || UserManager::getCurrentUser()->isSuperAdmin() || UserManager::getCurrentUser()->isEngineer()) {
           
                return true;
          
        }
        return false;

    }

  public function getPdf()
  {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
    //require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/dompdf/autoload.inc.php';


    $oMPDF = new \Mpdf\Mpdf([
      'tempDir' => sys_get_temp_dir(),
      'mode' => 'utf-8',
      'format' => 'A4-L']);
    //$oMPDF->showImageErrors = true;


    $oCustomer = CustomerManager::getCustomerById($this->customerId);
    $oUser = UserManager::getUserById($this->userId);
    $oAppointment = $this;

    $aFilter['customerId'] = $this->customerId;
    $aFilter['orderBy'] = ['cast(`s`.`pos` as unsigned)' => 'ASC', '`s`.`pos`' => 'ASC', '`s`.`name`' => 'ASC', '`s`.`systemId`' => 'DESC'];
    $aFilter['showAll'] = 1;
    $aSystems = SystemManager::getSystemsByFilter($aFilter);

    $oMPDF->shrink_tables_to_fit = 1;
    //$oMPDF->setAutoTopMargin     = 'stretch';
    //$oMPDF->margin-top = 10mmzz;
    $oMPDF->curlAllowUnsafeSslRequests = true;

    define("DOMPDF_ENABLE_REMOTE", false);
    $sStylesheet                 = file_get_contents(DOCUMENT_ROOT . getAdminCss('pdf', 'customers'));
    $oMPDF->WriteHTML($sStylesheet, 1);
/*
    ob_start();
    include_once getAdminSnippet('pdfHeader', 'customers');
    $sHeader = ob_get_contents();
    ob_end_clean();
    $oMPDF->SetHTMLHeader($sHeader);
*/
    ob_start();
    include_once getAdminSnippet('pdfBody', 'customers');
    $sHtml = ob_get_contents();
    ob_end_clean();

    if (http_get('html') == 1) {
      echo $sHtml;
      die;
    }

    $oMPDF->WriteHTML($sHtml);
//echo $sHtml; die;
    //$sFooter = file_get_contents(getAdminSnippet('pdfFooter', 'customers'));
    //$oMPDF->SetHTMLFooter($sFooter);

    return $oMPDF;
  }

}