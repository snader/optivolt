<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">
                <span class="float-right">
                    <a class="backBtn right pl-1" href="<?= ADMIN_FOLDER ?>/planning">
                        <button type="button" class="btn btn-default btn-sm" title="Naar loggers">
                            Naar loggersplanning
                        </button>
                    </a>
                </span>
                <h1 class="m-0"><i aria-hidden="true" class="fa fas fa-tachometer-alt fa-th-large"></i>&nbsp;&nbsp;Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<?php
if (UserManager::getCurrentUser()->userAccessGroupId == 2) {
    // ONDERHOUDSMONTEURS
    include_once getAdminSnippet('dashboardEngineers');
} else {
    // ADMINS
    include_once getAdminSnippet('dashboardAdmins');
}

?>

<?php

$oPageLayout->addJavascript(
    '
<script>
if (top.location != location) {
    top.location.href = document.location.href ;
}
</script>
'
);
?>