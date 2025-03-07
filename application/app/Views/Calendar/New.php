<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>証券 新規登録</h1>
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
        <form role="form" method="POST" action="<?= site_url('calendar/create/') ?>">
          <div class="box-body">
            <div class="form-group<?=(!$validation->hasError('id'))?'': ' has-error' ?>">
              <label for="inputId">証券番号</label>
              <input type="text" class="form-control" name="id" id="inputId" placeholder="825123456" value="<?= set_value('id') ?>" maxlength="9" required>
              <span class="help-block"><?= $validation->showError('id') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('name'))?'': ' has-error' ?>">
              <label for="inputName">被保険者名</label>
              <input type="text" class="form-control" name="name" id="inputName" placeholder="アクサ 太郎" value="<?= set_value('name') ?>" required>
              <span class="help-block"><?= $validation->showError('name') ?></span>
            </div>
            <div class="form-group<?=(!$validation->hasError('comment'))?'': ' has-error' ?>">
              <label for="inputComment">査定者メモ</label>
              <textarea class="form-control" rows="10" name="comment" id="inputComment" placeholder="自由入力欄"><?= set_value('comment') ?></textarea>
              <span class="help-block"><?= $validation->showError('comment') ?></span>
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
<?= $this->endSection() ?>

<?= $this->section('javascripts') ?>
<?= $this->endSection() ?>
