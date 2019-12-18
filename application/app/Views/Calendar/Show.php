<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?=$shoken['name'] ?>
    <small><span class="label label-success"><?=$shoken['id'] ?></span></small>
  </h1>
  <div class="breadcrumb" style="padding:0; top:10px; right:15px;">
    <a class="btn btn-success" href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" role="button">証券編集</a>
  </div>
</section>

<!-- Main content -->
<section class="content container-fluid">
  <div class="row">
    <div class="col-md-8">
      <div class="box-group">
        <div class="panel box box-primary">
          <div class="box-header with-border">
            <h4 class="box-title">
              年間カレンダー
              <?php if ($ukeban_id){ ?>
              <a href="<?= site_url("{$shoken['id']}/") ?>" class="btn btn-danger btn-xs">受番:<?=$ukeban_id ?></a>
              <?php } ?>
            </h4>
            <div class="box-tools pull-right">
              <div class="row">
                <div class="col-lg-6">
                  <!-- checkbox -->
                  <div class="form-group" style="margin:4px 0px 0px 0px;">
                    <label>
                      <input type="checkbox" class="flat-red" id="no-data" checked> 全表示
                    </label>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="input-group input-group-sm" style="width:200px; float:right;">
                    <input type="text" class="form-control monthrange" id="monthrange" name="monthrange" value="<?= date('Y/m', strtotime($date)) ?> - <?= date('Y/m') ?>" onchange="nenview()">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="nenview_reset()">解除</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box-body no-padding">
            <?php (new \App\Libraries\Calendar)->render($date, date('Y/m')); ?>
          </div>
          <!-- /.box-body -->
        </div>
        <div class="panel box box-danger">
          <!-- THE CALENDAR -->
          <div id="calendar"></div>
        </div>
      </div>
    </div>
    <!-- /.col -->

    <div class="col-md-4">
      <?php if($shoken['comment']){ ?>
      <div class="small-box bg-green">
        <div class="inner">
          <p style="margin-bottom:0px;"><?=nl2br($shoken['comment']) ?></p>
        </div>
        <a href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" class="small-box-footer">
          <i class="fa fa-pencil"></i>コメント編集
        </a>
      </div>
      <?php } ?>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#timeline" data-toggle="tab">受付</a></li>
          <li><a href="#result" data-toggle="tab">履歴</a></li>
          <li><a href="#final" data-toggle="tab">結果</a></li>
          <li><a href="#batch" data-toggle="tab">一括</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn-warning btn btn-block" href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" role="button">
                  <i class="fa fa-pencil margin-r-5"></i>契約日
                </a>
              </li>
              <!-- /.timeline-label -->
              <?php foreach($shoken['ukeban'] as $line){ ?>
              <!-- timeline item -->
              <li>
                <i class="fa fa-envelope bg-blue"></i>
                <div class="timeline-item">
                  <h3 class="timeline-header"><?= $line['id'] ?></h3>
                  <div class="timeline-body" style="padding-bottom: 0px;">
                    <?php if(!empty($line['nyuin'])){ ?>
                    <div class="box box-widget collapsed-box" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-hotel margin-r-5"></i>入院：<?= count($line['nyuin']) ?>件
                            </small>
                          </a>
                        </div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach($line['nyuin'] as $i){ ?>
                          <li><?= $i['start'] ?> - <?= $i['end'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if(!empty($line['shujutsu'])){ ?>
                    <div class="box box-widget collapsed-box" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-calendar-times-o margin-r-5"></i>手術：<?= count($line['shujutsu']) ?>件
                            </small>
                          </a>
                        </div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach($line['shujutsu'] as $i){ ?>
                          <li><?= $i['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if(!empty($line['tsuin'])){ ?>
                    <div class="box box-widget collapsed-box" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-taxi margin-r-5"></i>通院：<?= count($line['tsuin']) ?>件
                            </small>
                          </a>
                        </div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach($line['tsuin'] as $i){ ?>
                          <li><?= $i['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } ?>
                    <?php if(!empty($line['bunsho'])){ ?>
                    <div class="box box-widget collapsed-box" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-pencil-square-o margin-r-5"></i>非該当通院：<?= count($line['bunsho']) ?>件
                            </small>
                          </a>
                        </div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach($line['bunsho'] as $i){ ?>
                          <li><?= $i['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } ?>
                  </div>
                  <div class="timeline-footer">
                    <?php if($line['id'] != $ukeban_id){ ?>
                    <a href="<?= site_url("{$shoken['id']}/{$line['id']}/") ?>" class="btn btn-danger btn-xs">これだけカレンダーに表示</a>
                    <?php } ?>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <?php } ?>

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn btn-success btn-block" href="<?= site_url("calendar/ukeban/{$shoken['id']}/") ?>" role="button">
                  <i class="fa fa-envelope-o margin-r-5"></i>受付番号 登録
                </a>
              </li>
              <!-- /.timeline-label -->
            </ul>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="result">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn-warning btn btn-block" href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" role="button">
                  <i class="fa fa-pencil margin-r-5"></i>契約日
                </a>
              </li>
              <!-- /.timeline-label -->
              <?php foreach($shoken['ukeban'] as $line){ ?>
              <!-- timeline item -->
              <li>
                <i class="fa fa-bank bg-red"></i>
                <div class="timeline-item">
                  <h3 class="timeline-header"><?= $line['id'] ?></h3>
                  <div class="timeline-body">
                    <?php if (isset($line['warranty'])) { ?>
                    <?php foreach ($line['warranty'] as $key => $warranty) { ?>
                    <?php if ($warranty['type'] == 'nyuin' && count($warranty['warranty'])) { ?>
                    <div class="box box-success collapsed-box" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-hotel margin-r-5"></i>入院：<?=$warranty['date'] ?>
                            </small>
                          </a>
                        </div>
                        <div class="description"><?= reset($warranty['warranty'])['date'] ?> - <?= end($warranty['warranty'])['date'] ?> (<?= count($warranty['warranty']) ?>日)</div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach ($warranty['warranty'] as $tsuin) { ?>
                          <li><?= $tsuin['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } elseif ($warranty['type'] == 'shujutsu' && count($warranty['warranty'])) { ?>
                    <div class="box box-success collapsed-box" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-calendar-times-o margin-r-5"></i>手術：<?=$warranty['date'] ?>
                            </small>
                          </a>
                        </div>
                        <div class="description"><?= reset($warranty['warranty'])['date'] ?> - <?= end($warranty['warranty'])['date'] ?> (<?= count($warranty['warranty']) ?>日)</div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach ($warranty['warranty'] as $tsuin) { ?>
                          <li><?= $tsuin['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } elseif ($warranty['type'] == 'other') { ?>
                    <div class="box <?=count($warranty['warranty']) ? 'box-danger' : 'box-default' ?> box-solid" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-times-circle margin-r-5"></i>保障外 <?= count($warranty['warranty']) ?>日
                            </small>
                          </a>
                        </div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach ($warranty['warranty'] as $tsuin) { ?>
                          <li><?= $tsuin['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } elseif ($warranty['type'] == 'ban' && count($warranty['warranty'])) { ?>
                    <div class="box box-danger box-solid" style="margin-bottom: 5px">
                      <div class="box-header with-border">
                        <div class="box-title">
                          <a data-widget="collapse" href="#">
                            <small>
                              <i class="fa fa-times-circle margin-r-5"></i>過払い <?= count($warranty['warranty']) ?>日
                            </small>
                          </a>
                        </div>
                      </div>
                      <div class="box-body">
                        <ol>
                          <?php foreach ($warranty['warranty'] as $tsuin) { ?>
                          <li><?= $tsuin['date'] ?></li>
                          <?php } ?>
                        </ol>
                      </div>
                    </div>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <?php } ?>

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn btn-success btn-block" href="<?= site_url("calendar/ukeban/{$shoken['id']}/") ?>" role="button">
                  <i class="fa fa-envelope-o margin-r-5"></i>受付番号 登録
                </a>
              </li>
              <!-- /.timeline-label -->
            </ul>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="final">
            <div class="box collapsed-box box-success box-solid" style="margin-bottom: 5px">
              <div class="box-header with-border">
                <div class="box-title">
                  <a data-widget="collapse" href="#">
                    <small>
                      <i class="fa fa-times-circle margin-r-5"></i>支払済み <?= count($shoken['warranty']) ?>日
                    </small>
                  </a>
                </div>
              </div>
              <div class="box-body">
                <ol>
                  <?php foreach ($shoken['warranty'] as $tsuin) { ?>
                  <li><?= $tsuin['date'] ?></li>
                  <?php } ?>
                </ol>
              </div>
            </div>
            <div class="box <?=count($shoken['other']) ? 'box-danger' : 'box-default' ?> box-solid" style="margin-bottom: 5px">
              <div class="box-header with-border">
                <div class="box-title">
                  <a data-widget="collapse" href="#">
                    <small>
                      <i class="fa fa-times-circle margin-r-5"></i>保障外 <?= count($shoken['other']) ?>日
                    </small>
                  </a>
                </div>
              </div>
              <div class="box-body">
                <ol>
                  <?php foreach ($shoken['other'] as $tsuin) { ?>
                  <li><?= $tsuin['date'] ?></li>
                  <?php } ?>
                </ol>
              </div>
            </div>
            <div class="box collapsed-box box-default box-solid" style="margin-bottom: 5px">
              <div class="box-header with-border">
                <div class="box-title">
                  <a data-widget="collapse" href="#">
                    <small>
                      <i class="fa fa-times-circle margin-r-5"></i>非該当通院 <?= count($shoken['bunsho']) ?>日
                    </small>
                  </a>
                </div>
              </div>
              <div class="box-body">
                <ol>
                  <?php foreach ($shoken['bunsho'] as $tsuin) { ?>
                  <li><?= $tsuin['date'] ?></li>
                  <?php } ?>
                </ol>
              </div>
            </div>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="batch">
            <?php if(isset($line)){ ?>
            <div class="box-body">
              <form class="form" original-action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/") ?>" method="POST">
                <div class="form-group">
                  <label>受付番号</label>
                  <select class="form-control" id="batch-ukeban">
                    <?php foreach($shoken['ukeban'] as $line){ ?>
                    <option value="<?=$line['id'] ?>" <?= ($line['id'] == $ukeban_id) ? '' : 'selected' ?>><?=$line['id'] ?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>通院日</label>
                  <textarea class="form-control" rows="10" name="date" placeholder="2019-09-10
2019-10-10
2019-11-10

2019/01/01,2019-01-02,2019-01-13
2019/02/01 2019-02-02 2019/02/13" required></textarea>
                </div>
                <!-- /.form group -->
                <button type="submit" id="batch-submit" class="btn btn-success btn-block">
                  <i class="fa fa-taxi margin-r-5"></i>通院 一括登録
                </button>
              </form>
            </div>
            <?php } ?>
          </div>
          <!-- /.tab-pane -->

        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->

  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

<?php if(isset($line)){ ?>
<div class="modal" id="delete-modal" data-keyboard="true" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="delete-modal-form" original-action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/") ?>" method="POST">
        <div class="modal-header bg-red">
          <h4 class="modal-title">イベント削除</h4>
        </div>
        <div class="modal-body" style="background-color:white !important:">
          <div class="form-group">
            <label>受付番号</label>
            <div class="input-group date">
              <input type="text" name="date" class="form-control pull-right" id="delete-modal-ukeban" readonly>
            </div>
            <!-- /.input group -->
          </div>
          <div class="form-group">
            <label>種別</label>
            <div class="radio">
              <label class="form-control">
                <input type="radio" class="icheck" name="type" id="delete-modal-nyuin" value="nyuin" disabled>
                <i class="fa fa-hotel margin-r-5"></i>入院
              </label>
            </div>
            <div class="radio">
              <label class="form-control">
                <input type="radio" class="icheck" name="type" id="delete-modal-shujutsu" value="shujutsu" disabled>
                <i class="fa fa-calendar-times-o margin-r-5"></i>手術
              </label>
            </div>
            <div class="radio">
              <label class="form-control">
                <input type="radio" class="icheck" name="type" id="delete-modal-tsuin" value="tsuin" disabled>
                <i class="fa fa-taxi margin-r-5"></i>通院
              </label>
            </div>
            <div class="radio">
              <label class="form-control">
                <input type="radio" class="icheck" name="type" id="delete-modal-bunsho" value="bunsho" disabled>
                <i class="fa fa-pencil-square-o margin-r-5"></i>非該当通院
              </label>
            </div>
          </div>
          <div class="form-group">
            <label>日付</label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="date" class="form-control pull-right" id="delete-modal-date" readonly>
            </div>
            <!-- /.input group -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">キャンセル</button>
          <button type="submit" id="delete-modal-submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">削除</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal" id="create-modal" data-keyboard="true" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="create-modal-form" action="<?= site_url("calendar/event/{$shoken['id']}/") ?>" method="POST">
        <div class="modal-header bg-green">
          <h4 class="modal-title">イベント登録</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>受付番号</label>
            <select class="form-control" name="ukeban_id">
              <?php foreach($shoken['ukeban'] as $line){ ?>
              <option value="<?=$line['id'] ?>" <?= ($line['id'] == $ukeban_id) ? '' : 'selected' ?>><?=$line['id'] ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label>種別</label>
            <div class="radio">
              <label class="form-control">
                <input type="radio" class="icheck" name="type" id="create-modal-nyuin" value="nyuin" checked required>
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
          </div>
          <div class="form-group" id="create-modal-date-div">
            <label>日付</label>
            <div class="input-group date">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="date" class="form-control pull-right datepicker" id="create-modal-date">
            </div>
            <!-- /.input group -->
          </div>
          <div class="form-group" id="create-modal-range-div">
            <label>入院期間</label>
            <div class="input-group">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" name="daterange" class="form-control pull-right daterange" id="create-modal-range">
            </div>
            <!-- /.input group -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">キャンセル</button>
          <button type="submit" id="create-modal-submit" class="btn btn-success">登録</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<?php } ?>

<?= $this->endSection() ?>

<?= $this->section('stylesheets') ?>
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<!-- bootstrap daterangepicker -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/bootstrap-monthrangepicker/daterangepicker.css">
<!-- fullCalendar -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.min.css">
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
<!-- iCheck for checkboxes and radio inputs -->
<link rel="stylesheet" href="/vendor/adminlte-2.4.18/plugins/iCheck/all.css">
<!-- tippy theme -->
<link rel="stylesheet" href="/vendor/tippy/light-border.css">

<style>
  .day-warranty {
      text-decoration: underline;
      font-weight: bold;
      color: green;
  }
  .day-tsuin {
      background-color: #00a65a;
      color: white;
  }
  .day-other {
      background-color: #ff64c8;
  }
  .day-nyuin {
      background-color: #0000ff;
      color: white;
  }
  .day-bunsho {
      background-color: #a0a0a0;
  }
  .day-shujutsu {
      color: red;
  }
</style>
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
<script src="/vendor/adminlte-2.4.18/bower_components/bootstrap-monthrangepicker/daterangepicker.js"></script>
<!-- fullCalendar -->
<script src="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="/vendor/adminlte-2.4.18/bower_components/fullcalendar/dist/locale-all.js"></script>
<!-- tippy -->
<script src="/vendor/tippy/popper.js"></script>
<script src="/vendor/tippy/tippy.js"></script>
<!-- iCheck -->
<script src="/vendor/adminlte-2.4.18/plugins/iCheck/icheck.min.js"></script>
<script>
  // ２つのカレンダーに表示するために、Ajaxをあきらめた（言い訳）
  var eventData = [
      {
          id: 'warranty',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['shujutsu'], ['ukeban_id' => $ukeban_id], 'warrantyStart', 'warrantyEnd', $shoken['exclude']) ?>,
          rendering: 'background',
      },
      {
          id: 'warranty',
          events: <?= \App\Libraries\Smartcare::toJsonEvents(\App\Libraries\Smartcare::conbineNyuin($shoken['nyuin']), ['ukeban_id' => $ukeban_id], 'warrantyStart', 'warrantyEnd', $shoken['exclude']) ?>,
          rendering: 'background',
      },
      {
          id: 'tsuin',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['warranty'], ['ukeban_id' => $ukeban_id], 'date', 'date') ?>,
          color: "#00a65a",
          description: "通院",
      },
      {
          id: 'other',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['other'], ['ukeban_id' => $ukeban_id], 'date', 'date') ?>,
          color: "#ff64c8",
          description: "支払えない通院",
      },
      {
          id: 'nyuin',
          events: <?= \App\Libraries\Smartcare::toJsonEvents(\App\Libraries\Smartcare::conbineNyuin($shoken['nyuin']), ['ukeban_id' => $ukeban_id], 'start', 'end') ?>,
          color: "#0000ff",
          description: "入院",
      },
      {
          id: 'shujutsu',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['shujutsu'], ['ukeban_id' => $ukeban_id], 'date', 'date') ?>,
          color: "red",
          description: "手術",
      },
      {
          id: 'bunsho',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['bunsho'], ['ukeban_id' => $ukeban_id], 'date', 'date') ?>,
          color: "#a0a0a0",
          description: "非該当通院",
      },
  ];

  $(function () {
      eventData.forEach(function(events){
          events.events.forEach(function(event){
              if (!event.start) return;
              start = event.start.split('-');
              end = event.end.split('-');

              start = new Date(start[0], start[1]-1, start[2]);
              end = new Date(end[0], end[1]-1, end[2]);

              while (start < end) {
                  $("#day-"+$.datepicker.formatDate("yy-m-dd", start))
                      .addClass('day-' + events.id);
                  if (!events.rendering) {
                      event.source = events;
                      list = $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).data('event') || [];
                      list.push(event);
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start))
                          .data('event', list);

                      if (events.id == 'shujutsu') {
                          code = parseInt($("#day-"+$.datepicker.formatDate("yy-m-dd", start)).text());
                          if (code > 20) {
                              code += 12860;
                          } else {
                              code += 9311;
                          }
                          $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).html(
                              '&#' + code + ';');
                      }
                  }

                  // 通院の時は通院数をカウントアップ
                  if (events.id == "tsuin") {
                      $("#sum-"+$.datepicker.formatDate("yy-m", start)).text(
                          parseInt($("#sum-"+$.datepicker.formatDate("yy-m", start)).text(), 10) + 1
                      ).addClass('bg-red');
                  }
                  $("#sum-"+$.datepicker.formatDate("yy-m", start)).removeClass('no-data');
                  start.setDate(start.getDate() + 1);
              }
          });
      });

      $('#nenview').fadeTo(0, 1);

      tippy('.day', {
          content: function (reference) {
              list = $(reference).data('event') || [];
              title = [];
              list.forEach(function(event){
                  title.push(
                      event.description ?
                          event.description :
                          event.source.description);
              });
              return title.join(', ');
          },
          onShow: function (options) {
              return !!options.props.content;
          },
          performance: true,
          animation: false,
          duration: 0,
          delay: 0,
          placement: "top",
          multiple: true,
      });

      tippy('.day', {
          content: function (reference) {
              list = $(reference).data('event') || [];
              date = $(reference).attr('id').split('-');
              date = new Date(date[1], date[2]-1, date[3]);
              link = 'javascript:createmodal(\'' + $.datepicker.formatDate("yy-mm-dd", date) + '\');tippy.hideAll();';
              title = [
                  '<div class="box-body">' + $.datepicker.formatDate("yy/mm/dd", date) + '</div>',
                  '<a class="btn btn-success btn-block" href="' + link + '"><i class="fa fa-calendar-plus-o margin-r-5"></i>登録</a>',
              ];
              list.forEach(function(event){
                  icons = {
                      tsuin: '<i class="fa fa-taxi margin-r-5"></i>通院',
                      other: '<i class="fa fa-taxi margin-r-5"></i>通院',
                      shujutsu: '<i class="fa fa-calendar-times-o margin-r-5"></i>手術',
                      nyuin: '<i class="fa fa-hotel margin-r-5"></i>入院',
                      bunsho: '<i class="fa fa-pencil-square-o margin-r-5"></i>非該当通院',
                  };
                  link = 'javascript:deletemodalByDate(\'' + $.datepicker.formatDate("yy-m-dd", date) + '\', \'' + event.source.id + '\');tippy.hideAll();';
                  title.push('<a class="btn btn-danger btn-block" href="' + link + '">'+icons[event.source.id]+'</a>');
              });
              return title.join('');
          },
          onShow: function (options) {
              list = $(options.reference).data('event') || [];
              if (list.length == 0) {
                  date = $(options.reference).attr('id').split('-');
                  date = new Date(date[1], date[2]-1, date[3])
                  createmodal($.datepicker.formatDate("yy-mm-dd", date));
                  return false;
              }

              return true;
          },
          performance: true,
          animation: false,
          duration: 0,
          delay: 0,
          theme: "light-border",
          interactive: true,
          appendTo: document.body,
          placement: "bottom",
          trigger: "click",
          multiple: true,
      });
  });

  $(window).on('load', function(){
      $('.daterange').daterangepicker({
          autoApply: true,
          drops: 'down',
          locale: {
              format:'YYYY/MM/DD',
              applyLabel: '反映',
              cancelLabel: '取消',
              daysOfWeek: ['日', '月', '火', '水', '木', '金', '土'],
              monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
          },
      });

      $('.datepicker').datepicker({
          autoclose: true,
          orientation: 'bottom',
          language: 'ja',
      });

      $('.monthrange').monthrangepicker({
          autoApply: true,
          drops: 'down',
          minYear: <?= date('Y', strtotime($date)) ?>,
          maxYear: <?= date('Y') ?>,
          locale: {
              format:'YYYY/MM',
              applyLabel: '反映',
              cancelLabel: '取消',
              monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
          }
      });

      $('.flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass   : 'iradio_flat-green'
      });

      $('.icheck').iCheck({
          checkboxClass: 'icheckbox_flat-red',
          radioClass   : 'iradio_flat-red'
      });

      $('#no-data').on('ifChanged', function(){
          nenview();
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

      $('#batch-submit').on('click', function(event){
          form = $(this).parents('form');
          params = form.serializeArray();
          ukeban = null;
          params.forEach(function(param){
              if (param.name == 'ukeban_id') {
                  ukeban = param.value;
              }
          });
          form.attr('action', form.attr('original-action') + $('#batch-ukeban option:selected').val() + '/batch');
          return true;
      });

      $('#calendar').fullCalendar({
          views: {
              month: {
                  columnFormat: 'ddd',
                  titleFormat: 'YYYY年M月',
              },
          },
          buttonText: {
              today: '今日',
          },
          header: {
              left: 'title',
              center: '',
              right: 'prev,next'
          },
          selectable: true,
          dayNames: ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'],
          dayNamesShort: ['日', '月', '火', '水', '木', '金', '土'],
          eventSources: eventData,
          eventRender: function (info, element) {
              if ($(element).hasClass('fc-bgevent')) {
                  return true;
              }

              title = info.description;
              if (!title) {
                  eventData.forEach(function(events){
                      if (events.color == info.source.color) {
                          title = events.description;
                      }
                  });
              }

              $(element).prop('title', title);
              tippy($(element)[0], {
                  content: function (reference) {
                      return reference.getAttribute('title');
                  },
                  onShow: function (options) {
                      return !!options.props.content
                  },
                  performance: true,
                  duration: [100, 50],
              });
          },
          select: function(start, end) {
              createmodal(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
          },
          eventClick: function(info) {
              deletemodal(info, info.source)
          }
      });
  });

  function createmodal(start, end) {
      if ($('#create-modal').length == 0) {
          return location.href = '<?= site_url("calendar/ukeban/{$shoken['id']}/") ?>';
      }

      $('#create-modal').modal();

      if (end) {
          end = end.split('-');
          end = new Date(end[0], end[1]-1, end[2]-1);
          end = $.datepicker.formatDate("yy-m-dd", end);
      }
      if (end && start != end) {
          $('#create-modal-nyuin').iCheck('check');
          $('#create-modal-date').datepicker("setDate", start);
          $('#create-modal-range').data('daterangepicker').setStartDate(start);
          $('#create-modal-range').data('daterangepicker').setEndDate(end);
      } else {
          $('#create-modal-tsuin').iCheck('check');
          $('#create-modal-date').datepicker("setDate", start);
          $('#create-modal-range').data('daterangepicker').setStartDate(start);
          $('#create-modal-range').data('daterangepicker').setEndDate(start);
      }
  }

  function deletemodalByDate(date, type){
      list = $("#day-"+date).data('event');
      list.forEach(function(event){
          if (event.source.id == type) {
              deletemodal(event, event.source);
          }
      });
  }

  function deletemodal(event, source){
      type = (source.id == 'other') ?
          'tsuin' :
          source.id;
      $('#delete-modal').modal();
      $('#delete-modal-tsuin').iCheck('disable');
      $('#delete-modal-bunsho').iCheck('disable');
      $('#delete-modal-shujutsu').iCheck('disable');
      $('#delete-modal-nyuin').iCheck('disable');
      $('#delete-modal-form').attr('action', $('#delete-modal-form').attr('original-action') + event.title + '/' + type + '/' + event.event_id + '/delete');
      $('#delete-modal-'+type).iCheck('check');
      $('#delete-modal-'+type).iCheck('enable');
      $('#delete-modal-ukeban').val(event.title);

      if (type == 'nyuin') {
          if (event.start instanceof moment) {
              end = event.end.format('YYYY-MM-DD').split('-');
              end = new Date(end[0], end[1]-1, end[2]-1);
              end = $.datepicker.formatDate("yy-mm-dd", end);
              $('#delete-modal-date').val(event.start.format('YYYY-MM-DD') + ' - ' + end);
          } else {
              end = event.end.split('-');
              end = new Date(end[0], end[1]-1, end[2]-1);
              end = $.datepicker.formatDate("yy-mm-dd", end);
              $('#delete-modal-date').val(event.start + ' - ' + end);
          }
      } else {
          if (event.start instanceof moment) {
              $('#delete-modal-date').val(event.start.format('YYYY-MM-DD'));
          } else {
              $('#delete-modal-date').val(event.start);
          }
      }
  }

  function nenview() {
      $('.no-data').parent().parent().show();
      $('.no-data').parent().parent().next().show();

      s_range = $('#monthrange').val().split(' - ');
      start = s_range[0].split('/');
      start = new Date(start[0], start[1]);
      end = s_range[1].split('/');
      end = new Date(end[0], end[1]);

      $('#nenview tr').each(
          function(index, element){
              line = $(element).attr('id').split('-');
              line = new Date(line[1], line[2])
              if (start <= line && line <= end) {
                  $(element).show();
              } else {
                  $(element).hide();
              }
          });

      if (!$('#no-data').prop("checked")) {
          $('.no-data').parent().parent().hide();
          $('.no-data').parent().parent().next().hide();
      }
  }

  function nenview_reset() {
      $('#monthrange').data('daterangepicker').setStartDate('<?= date('Y/m', strtotime($date)) ?>');
      $('#monthrange').data('daterangepicker').setEndDate('<?= date('Y/m') ?>');
  }

  function month(target) {
      $("html,body").animate({scrollTop:$('#calendar').offset().top});
      $('#calendar').fullCalendar('gotoDate', target);
  }
</script>
<?= $this->endSection() ?>
