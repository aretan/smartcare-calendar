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
              <input type="text" class="form-control" name="id" id="inputId" placeholder="E121234123456" value="<?= set_value('id') ?>" maxlength="14" required>
              <span class="help-block"><?= $validation->showError('id') ?></span>
            </div>
          </div>
          <!-- /.box-body -->

          <div class="box-body no-padding">
            <?php (new \App\Libraries\Calendar)->render('2017/11', date('Y/m')); ?>
          </div>

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
<style>
  .day {
      -moz-user-select: -moz-none;
      -khtml-user-select: none;
      -webkit-user-select: none;
      -o-user-select: none;
      user-select: none;
  }
  .day-nyuin {
      color: red !important;
  }
  .day-shujutsu {
      background-color: green;
  }
  .day-tsuin {
      background-color: #ff64c8;
  }
  .day-bunsho {
      background-color: #a0a0a0;
  }
</style>
<?= $this->endSection() ?>

<?= $this->section('javascripts') ?>
<!-- tippy -->
<script src="/vendor/tippy/popper.js"></script>
<script src="/vendor/tippy/tippy.js"></script>

<script>
  var eventType = [
      'nyuin',
      'shujutsu',
      'tsuin',
      'bunsho',
      'none',
  ];
  var eventName = [
      '入院',
      '手術',
      '通院',
      '非該当通院',
      '',
  ];

  $(function () {
      $('.day').on('click', function(event){
          type = $(event.target).data('type');
          if (type == undefined) {
              type = 4;
          }
          type ++;

          $(event.target).data('type', type);

          $(event.target).attr('class', 'day day-'+eventType[type%5]);
      }).on('contextmenu', function(event) {
          type = 4;
          $(event.target).data('type', type);
      });

      $('#nenview').fadeTo(0, 1);
  });
</script>
<?= $this->endSection() ?>
