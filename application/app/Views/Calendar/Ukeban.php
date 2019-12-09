<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>受付番号 新規登録</h1>
</section>

<!-- Main content -->
<section class="content">
  <?php if($validation->getErrors()) { ?>
  <div class="callout callout-danger">
    <h4>入力エラー</h4>
    <?= $validation->listErrors() ?>
  </div>
  <?php } ?>
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <!-- form start -->
        <form role="form" method="POST" action="<?= site_url('calendar/ukeban/') ?>">
          <div class="box-body">
            <div class="form-group<?=(!$validation->hasError('shoken_id'))?'': ' has-error' ?>">
              <label for="inputShokenId">証券番号</label>
              <input type="text" class="form-control" name="shoken_id" id="inputShokenId" placeholder="825123456" value="<?= set_value('shoken_id', $shoken_id) ?>" maxlength="9" readonly>
              <span class="help-block"><?= $validation->showError('shoken_id') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('id'))?'': ' has-error' ?>">
              <label for="inputId">受付番号</label>
              <input type="text" class="form-control" name="id" id="inputId" placeholder="E121234123456" value="<?= set_value('id') ?>" maxlength="14" required autofocus>
              <span class="help-block"><?= $validation->showError('id') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('date'))?'': ' has-error' ?>">
              <label for="inputDate">受付日</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" class="form-control datepicker" name="date" id="inputDate" placeholder="2018/07/06" value="<?= set_value('date') ?>">
              </div>
              <span class="help-block"><?= $validation->showError('date') ?></span>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-footer">
            <button type="submit" class="btn btn-primary">登録</button>
          </div>
        </form>
      </div>
      <!-- /.box -->

    </div>
    <!-- /.col -->

  </div>
  <!-- /.row -->
</section>
<!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<?= $this->endSection() ?>

<?= $this->section('javascripts') ?>
<!-- bootstrap datepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ja.min.js"></script>

<script>
  $(function () {
      //Date picker
      $('.datepicker').datepicker({
          autoclose: true,
          orientation: 'bottom',
          language: 'ja',
      })

  });
</script>
<?= $this->endSection() ?>
