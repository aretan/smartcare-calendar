<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>ダッシュボード</h1>
</section>

<!-- Main content -->
<section class="content">

  <div class="callout callout-info">
    <h4>状況</h4>
    特に計算に時間がかかる証券が選定され、データベースに登録されています。
  </div>

  <div class="row">
    <div class="col-xs-12">
      <div class="box box-default">
        <div class="box-header with-border">
          <h3 class="box-title">クイックスタート</h3>
        </div>
        <div class="box-body">
          リストから証券番号を選択するか、証券番号を入力してください。
        </div>
      </div>
    </div>
  </div>

</section>
<!-- /.content -->
<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<?= $this->endSection() ?>

<?= $this->section('javascripts') ?>
<?= $this->endSection() ?>
