

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
    <?php
    if (Customer::getCurrent()) { ?>
      <strong><?= Customer::getCurrent()->companyName ?></strong> | <?= Customer::getCurrent()->companyAddress ?>, <?= Customer::getCurrent()->companyPostalCode ?>, <?= Customer::getCurrent()->companyCity ?>
    <?php }?>
    </div>
    <strong>&copy; 2023 - <a href="https://optivolt.nl">Optivolt</a>.</strong> 
  </footer>    