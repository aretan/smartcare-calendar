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
                    <input type="text" class="form-control monthrange" id="monthrange" name="monthrange" value="<?= date('Y/m', strtotime($shoken['date'])) ?> - <?= date('Y/m') ?>" onchange="nenview()">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="nenview_reset()">解除</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="box-body no-padding">
            <?php (new \App\Libraries\Calendar)->render($shoken['date'], date('Y/m')); ?>
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
      <div class="callout callout-info">
        <?=$shoken['comment'] ?>
      </div>
      <?php } ?>
      <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#timeline" data-toggle="tab">受付</a></li>
          <li><a href="#result" data-toggle="tab">支払履歴</a></li>
          <li><a href="#final" data-toggle="tab">最終結果</a></li>
          <li><a href="#batch" data-toggle="tab">バッチ</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn-warning btn btn-block" href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" role="button">
                  <i class="fa fa-pencil margin-r-5"></i>契約日 (<?= $shoken['date'] ?>)
                </a>
              </li>
              <!-- /.timeline-label -->
              <?php foreach($shoken['ukeban'] as $line){ ?>
              <!-- timeline item -->
              <li>
                <i class="fa fa-envelope bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> <?= $line['date'] ?></span>
                  <h3 class="timeline-header"><?= $line['id'] ?></h3>
                  <div class="timeline-body" style="padding-bottom: 0px;">
                    <?php $nyuin = \App\Libraries\Smartcare::groupByUkebanId($shoken['nyuin']) ?>
                    <?php if(isset($nyuin[$line['id']])){ ?>
                    <i class="fa fa-hotel margin-r-5"></i>入院：<?= count($nyuin[$line['id']]) ?>件
                    <p>
                      <?php foreach($nyuin[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['start']) ?> -
                      <?= str_replace('-', '/', $i['end']) ?><br />
                      <?php } ?>
                    </p>
                    <?php } ?>
                    <?php $shujutsu = \App\Libraries\Smartcare::groupByUkebanId($shoken['shujutsu']) ?>
                    <?php if(isset($shujutsu[$line['id']])){ ?>
                    <i class="fa fa-calendar-times-o margin-r-5"></i>手術：<?= count($shujutsu[$line['id']]) ?>件
                    <p>
                      <?php foreach($shujutsu[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['date']) ?><br />
                      <?php } ?>
                    </p>
                    <?php } ?>
                    <?php $tsuin = \App\Libraries\Smartcare::groupByUkebanId($shoken['tsuin']) ?>
                    <?php if(isset($tsuin[$line['id']])){ ?>
                    <i class="fa fa-taxi margin-r-5"></i>通院：<?= count($tsuin[$line['id']]) ?>件
                    <p>
                      <?php foreach($tsuin[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['date']) ?>,
                      <?php } ?>
                    </p>
                    <?php } ?>
                    <?php $bunsho = \App\Libraries\Smartcare::groupByUkebanId($shoken['bunsho']) ?>
                    <?php if(isset($bunsho[$line['id']])){ ?>
                    <i class="fa fa-pencil-square-o margin-r-5"></i>文書：<?= count($bunsho[$line['id']]) ?>件
                    <p>
                      <?php foreach($bunsho[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['date']) ?>,
                      <?php } ?>
                    </p>
                    <?php } ?>
                  </div>
                  <div class="timeline-footer">
                    <a href="<?= site_url("{$shoken['id']}/{$line['id']}/") ?>" class="btn btn-danger btn-xs">これだけカレンダーに表示</a>
                  </div>
                </div>
              </li>
              <!-- END timeline item -->
              <?php } ?>

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn btn-success btn-block" href="<?= site_url("calendar/ukeban/{$shoken['id']}") ?>" role="button">
                  <i class="fa fa-envelope-o margin-r-5"></i>受付番号 登録
                </a>
              </li>
              <!-- /.timeline-label -->
            </ul>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="result">
            s
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="final">
            <?php foreach ($result as $key => $warranty) { ?>
            <?php if ($warranty['type'] == 'nyuin') { ?>
            <strong><i class="fa fa-hotel margin-r-5"></i>入院：<?=$warranty['date'] ?> 計<?= count($warranty['tsuin']) ?>日 残<?= $warranty['warrantyMax'] ?>日</strong>
            <p><small><?= implode(', ', $warranty['tsuin']) ?></small></p>
            <?php } elseif ($warranty['type'] == 'shujutsu') { ?>
            <strong><i class="fa fa-calendar-times-o margin-r-5"></i>手術：<?=$warranty['date'] ?> 計<?= count($warranty['tsuin']) ?>日 残<?= $warranty['warrantyMax'] ?>日</strong>
            <p><small><?= implode(', ', $warranty['tsuin']) ?></small></p>
            <?php } else { ?>
            <strong><i class="fa fa-times-circle margin-r-5"></i>保障外 計<?= count($warranty['tsuin']) ?>日</strong>
            <p><small><?= implode(', ', $warranty['tsuin']) ?></small></p>
            <?php } ?>
            <?php } ?>
          </div>
          <!-- /.tab-pane -->

          <div class="tab-pane" id="batch">
            <textarea></textarea>
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
<div class="modal fade" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="delete-modal-form" original-action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/") ?>" method="POST">
        <div class="modal-header bg-red">
          <h4 class="modal-title">カレンダー削除</h4>
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
            <div class="row">
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="delete-modal-tsuin" value="tsuin" readonly>
                <i class="fa fa-taxi margin-r-5"></i>通院
              </label>
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="delete-modal-bunsho" value="bunsho" readonly>
                <i class="fa fa-pencil-square-o margin-r-5"></i>文書
              </label>
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="delete-modal-shujutsu" value="shujutsu" readonly>
                <i class="fa fa-calendar-times-o margin-r-5"></i>手術
              </label>
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="delete-modal-nyuin" value="nyuin" readonly>
                <i class="fa fa-hotel margin-r-5"></i>入院
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

<div class="modal fade" id="create-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="create-modal-form" original-action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/") ?>" method="POST">
        <div class="modal-header bg-green">
          <h4 class="modal-title">カレンダー登録</h4>
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
            <div class="row">
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="create-modal-tsuin" value="tsuin" required>
                <i class="fa fa-taxi margin-r-5"></i>通院
              </label>
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="create-modal-bunsho" value="bunsho">
                <i class="fa fa-pencil-square-o margin-r-5"></i>文書
              </label>
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="create-modal-shujutsu" value="shujutsu">
                <i class="fa fa-calendar-times-o margin-r-5"></i>手術
              </label>
              <label class="col-md-3">
                <input type="radio" class="icheck" name="type" id="create-modal-nyuin" value="nyuin" checked>
                <i class="fa fa-hotel margin-r-5"></i>入院
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
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['shujutsu'], ['ukeban_id' => $ukeban_id], 'warrantyStart', 'warrantyEnd') ?>,
          rendering: 'background',
      },
      {
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['nyuin'], ['ukeban_id' => $ukeban_id], 'warrantyStart', 'warrantyEnd') ?>,
          rendering: 'background',
      },
      {
          id: 'tsuin',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($paypay, ['ukeban_id' => $ukeban_id]) ?>,
          color: "#00a65a",
          description: "通院",
      },
      {
          id: 'tsuin',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($no_pay, ['ukeban_id' => $ukeban_id]) ?>,
          color: "#ffaaaa",
          description: "支払えない通院",
      },
      {
          id: 'nyuin',
          events: <?= \App\Libraries\Smartcare::toJsonEvents(\App\Libraries\Smartcare::conbineNyuin($shoken['nyuin']), ['ukeban_id' => $ukeban_id]) ?>,
          color: "#00c0ef",
          description: "入院",
      },
      {
          id: 'shujutsu',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['shujutsu'], ['ukeban_id' => $ukeban_id]) ?>,
          color: "#dd4b39",
          description: "手術",
      },
      {
          id: 'bunsho',
          events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['bunsho'], ['ukeban_id' => $ukeban_id]) ?>,
          color: "#d2d6de",
          description: "文書",
      },
  ];

  $(function () {
      eventData.forEach(function(events){
          events.events.forEach(function(event){
              start = event.start.split('-');
              end = event.end.split('-');

              start = new Date(start[0], start[1]-1, start[2]);
              end = new Date(end[0], end[1]-1, end[2]);

              while (start < end) {
                  if (events.rendering) {
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start))
                          .css("text-decoration", 'underline')
                          .css("font-weight", 'bold')
                          .css("color", 'green');
                  } else {
                      color = event.color ? event.color : events.color;
                      if (events.color == "#dd4b39") { // 手術
                          $("#day-"+$.datepicker.formatDate("yy-m-dd", start))
                              .css("font-weight", 'bold')
                              .css("color", 'red')
                              .attr('onclick', '')
                              .on('click', function(){
                                  deletemodal(event, events)
                              });
                      } else {
                          $("#day-"+$.datepicker.formatDate("yy-m-dd", start))
                              .css("background-color", color)
                              .attr('onclick', '')
                              .on('click', function(){
                                  deletemodal(event, events);
                              });
                      }
                  }

                  description = event.description ? event.description : events.description;
                  if (description) {
                      title = $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).data('title');
                      if (!title) title = [];
                      title.push(description);
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start))
                          .data('title', title)
                          .prop('title', title.join(', '));
                  }
                  // 通院の時は通院数をカウントアップ
                  if (events.color == "#00a65a") {
                      $("#sum-"+$.datepicker.formatDate("yy-m", start)).text(
                          parseInt($("#sum-"+$.datepicker.formatDate("yy-m", start)).text(), 10) + 1
                      ).addClass('bg-red');
                  }
                  $("#sum-"+$.datepicker.formatDate("yy-m", start)).removeClass('no-data');
                  start.setDate(start.getDate() + 1);
              }
          });
      });
  });

  $(window).on('load', function() {
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
              title = info.description;
              if (!title) {
                  parent = eventData.find(function(events){ return events.color == info.source.color });
                  title = parent.description;
              }

              $(element).prop('title', title);
              tippy($(element)[0], {
                  content: function (reference) {
                      const title = reference.getAttribute('title');
                      reference.removeAttribute('title');
                      return title;
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

      tippy('#nenview>tbody>tr>td', {
          content: function (reference) {
              const title = reference.getAttribute('title');
              reference.removeAttribute('title');
              return title;
          },
          onShow: function (options) {
              return !!options.props.content
          },
          performance: true,
          duration: [100, 50],
      });

      //Date range picker
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

      //Date picker
      $('.datepicker').datepicker({
          autoclose: true,
          orientation: 'bottom',
          language: 'ja',
      });

      //Date picker
      $('.monthrange').monthrangepicker({
          autoApply: true,
          drops: 'down',
          minYear: <?= date('Y', strtotime($shoken['date'])) ?>,
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

      $('#create-modal-submit').on('click', function(event){
          form = $(this).parents('form');
          params = form.serializeArray();
          type = ukeban = null;
          params.forEach(function(param){
              if (param.name == 'type') {
                  type = param.value;
              }
              if (param.name == 'ukeban_id') {
                  ukeban = param.value;
              }
          });
          $(this).parents('form').attr('action', $(this).parents('form').attr('original-action') + ukeban + '/' + type);
          return true;
      });
      $('#nenview').fadeTo(0, 1);
  });

  function createmodal(start, end) {
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

  function deletemodal(event, source){
      $('#delete-modal').modal();
      $('#delete-modal-form').attr('action', $('#delete-modal-form').attr('original-action') + event.title + '/' + source.id + '/' + event.event_id + '/delete');
      $('#delete-modal-'+source.id).iCheck('check');
      $('#delete-modal-ukeban').val(event.title);

      if (source.id == 'nyuin') {
          if (event.start instanceof moment) {
              $('#delete-modal-date').val(event.start.format('YYYY/MM/DD') + ' - ' + event.end.format('YYYY/MM/DD'));
          } else {
              $('#delete-modal-date').val(event.start + ' - ' + event.end);
          }
      } else {
          if (event.start instanceof moment) {
              $('#delete-modal-date').val(event.start.format('YYYY/MM/DD'));
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
      $('#monthrange').data('daterangepicker').setStartDate('<?= date('Y/m', strtotime($shoken['date'])) ?>');
      $('#monthrange').data('daterangepicker').setEndDate('<?= date('Y/m') ?>');
  }

  function month(target) {
      $("html,body").animate({scrollTop:$('#calendar').offset().top});
      $('#calendar').fullCalendar('gotoDate', target);
  }

  // find polifill for IE11
  if (!Array.prototype.find) {
      Array.prototype.find = function(predicate) {
          if (this === null) {
              throw new TypeError('Array.prototype.find called on null or undefined');
          }
          if (typeof predicate !== 'function') {
              throw new TypeError('predicate must be a function');
          }
          var list = Object(this);
          var length = list.length >>> 0;
          var thisArg = arguments[1];
          var value;

          for (var i = 0; i < length; i++) {
              value = list[i];
              if (predicate.call(thisArg, value, i, list)) {
                  return value;
              }
          }
          return undefined;
      };
  }
</script>
<?= $this->endSection() ?>
