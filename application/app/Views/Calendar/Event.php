<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>イベント 新規登録</h1>
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
        <form role="form" method="POST" action="<?= site_url("calendar/event/{$shoken_id}/") ?>">
          <div class="box-body">
            <div class="form-group<?=(!$validation->hasError('shoken_id'))?'': ' has-error' ?>">
              <label for="inputId">証券番号</label>
              <input type="text" class="form-control" name="shoken_id" id="inputId" placeholder="825123456" value="<?= set_value('shoken_id', $shoken_id) ?>" maxlength="9" readonly>
              <span class="help-block"><?= $validation->showError('shoken_id') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('ukeban_id'))?'': ' has-error' ?>">
              <label>受付番号</label>
              <select class="form-control" name="ukeban_id">
                <?php foreach($ukeban as $line){ ?>
                <option value="<?=$line['id'] ?>" <?= ($line['id'] == $ukeban_id) ? 'selected' : '' ?>><?=$line['id'] ?> (<?=$line['date'] ?>)</option>
                <?php } ?>
              </select>
              <span class="help-block"><?= $validation->showError('ukeban_id') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('type'))?'': ' has-error' ?>">
              <label>種別</label>
              <div class="radio">
                <label class="form-control">
                  <input type="radio" class="icheck" name="type" id="create-modal-nyuin" value="nyuin" required checked>
                  <i class="fa fa-hotel margin-r-5"></i>入院
                </label>
              </div>
              <div class="radio">
                <label class="form-control">
                  <input type="radio" class="icheck" name="type" id="create-modal-shujutsu" value="shujutsu">
                  <i class="fa fa-calendar-times-o margin-r-5"></i>手術
                </label>
              </div>
              <div class="radio">
                <label class="form-control">
                  <input type="radio" class="icheck" name="type" id="create-modal-tsuin" value="tsuin">
                  <i class="fa fa-taxi margin-r-5"></i>通院
                </label>
              </div>
              <div class="radio">
                <label class="form-control">
                  <input type="radio" class="icheck" name="type" id="create-modal-bunsho" value="bunsho">
                  <i class="fa fa-pencil-square-o margin-r-5"></i>非該当通院
                </label>
              </div>
              <span class="help-block"><?= $validation->showError('type') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('date'))?'': ' has-error' ?>" id="create-modal-date-div">
              <label>日付</label>
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="date" class="form-control pull-right datepicker" id="create-modal-date" value="<?= set_value('date') ?>">
              </div>
              <!-- /.input group -->
              <span class="help-block"><?= $validation->showError('date') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('daterange'))?'': ' has-error' ?>" id="create-modal-range-div">
              <label>入院期間</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="daterange" class="form-control pull-right daterange" id="create-modal-range" value="<?= set_value('daterange') ?>">
              </div>
              <!-- /.input group -->
              <span class="help-block"><?= $validation->showError('daterange') ?></span>
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
<!-- bootstrap daterangepicker -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/plugins/iCheck/all.css">
<?= $this->endSection() ?>

<?= $this->section('javascripts') ?>
<!-- moment.js -->
<script src="/vendor/adminlte-2.4.18/bower_components/moment/moment.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/moment/locale/ja.js"></script>
<!-- bootstrap datepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ja.min.js"></script>
<!-- bootstrap daterangepicker -->
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- iCheck -->
<script src="/vendor/adminlte-2.4.18/plugins/iCheck/icheck.min.js"></script>

<script>
  $(function () {
      $('.daterange').daterangepicker({
          autoApply: true,
          opens: 'left',
          drops: 'up',
          locale: {
              format:'YYYY/MM/DD',
              applyLabel: '反映',
              cancelLabel: '取消',
              daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
              monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
          },
      });

      $('.icheck').iCheck({
          checkboxClass: 'icheckbox_flat-red',
          radioClass   : 'iradio_flat-red'
      });

      $('#create-modal-date-div').hide();
      $('#create-modal-nyuin').on('ifChecked', function(){
          $('#create-modal-date-div').hide();
          $('#create-modal-range-div').show();
          $('#create-modal-date').prop("disabled", true);
          $('#create-modal-range').prop("disabled", false);
      });
      $('#create-modal-nyuin').on('ifUnchecked', function(){
          $('#create-modal-date-div').show();
          $('#create-modal-range-div').hide();
          $('#create-modal-date').prop("disabled", false);
          $('#create-modal-range').prop("disabled", true);
      });

      $('#create-modal-<?=$type ?>').iCheck('check');
  });
</script>

<script>
  $(function () {
      //Date picker
      $('.datepicker').datepicker({
          autoclose: true,
          orientation: 'top',
          language: 'ja',
      })

  });
</script>
<?= $this->endSection() ?>
