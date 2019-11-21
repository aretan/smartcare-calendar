<?= $this->extend('Layouts/Main') ?> <!-- -*- mode:mhtml -*- -->
<?= $this->section('content') ?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?=$shoken['name'] ?>
    <small><?=$shoken['id'] ?></small>
  </h1>

  <div class="breadcrumb" style="padding:0; top:10px; right:15px;">
    <a class="btn btn-success" href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" role="button">証券編集</a>
  </div>
</section>

<!-- Main content -->
<section class="content container-fluid">
  <div class="row">
    <div class="col-md-8">
      <div class="box-group" id="accordion">
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-primary">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                年間カレンダー
              </a>

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
                    <input type="text" class="form-control" id="monthrange" name="monthrange" value="<?= date('Y/m', strtotime($shoken['date'])) ?> - <?= date('Y/m') ?>" onchange="nenview()">
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="nenview_reset()">解除</button>
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">

            <div class="box-body no-padding">
              <?php (new \App\Libraries\Calendar)->render($shoken['date'], date('Y/m')); ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <div class="panel box box-danger">
          <div class="box-header with-border">
            <h4 class="box-title">
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                月間カレンダー
              </a>
            </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse">
            <!-- THE CALENDAR -->
            <div id="calendar"></div>
          </div>
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
          <li class="active"><a href="#timeline" data-toggle="tab">受付番号</a></li>
          <li><a href="#activity" data-toggle="tab">通院数</a></li>
          <li><a href="#settings" data-toggle="tab">カレンダー</a></li>
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

          <div class="tab-pane" id="activity">
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

          <div class="tab-pane" id="settings">
            <?php if(isset($line)){ ?>
            <div class="box-header with-border">
              <h3 class="box-title"><?= $line['id'] ?> (最新)</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/{$line['id']}/bunsho") ?>" method="POST">
                <!-- Date -->
                <div class="form-group">
                  <label for="bunsho" class="col-sm-3 control-label"><i class="fa fa-pencil-square-o margin-r-5"></i>文書</label>
                  <div class="col-sm-9">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right datepicker" id="bunsho" data-date-format="yyyy/mm/dd" name="date" required>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>

              <form class="form-horizontal" action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/{$line['id']}/tsuin") ?>" method="POST">
                <!-- Date -->
                <div class="form-group">
                  <label for="tsuin" class="col-sm-3 control-label"><i class="fa fa-taxi margin-r-5"></i>通院</label>

                  <div class="col-sm-9">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right datepicker" id="tsuin" data-date-format="yyyy/mm/dd" name="date" required>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>

              <form class="form-horizontal" action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/{$line['id']}/shujutsu") ?>" method="POST">
                <!-- Date -->
                <div class="form-group">
                  <label for="shujutsu" class="col-sm-3 control-label"><i class="fa fa-calendar-times-o margin-r-5"></i>手術</label>

                  <div class="col-sm-9">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right datepicker" id="shujutsu" data-date-format="yyyy/mm/dd" name="date" required>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>

              <form class="form-horizontal" action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/{$line['id']}/nyuin") ?>" method="POST">
                <!-- Date range -->
                <div class="form-group">
                  <label for="nyuin" class="col-sm-3 control-label"><i class="fa fa-hotel margin-r-5"></i>入院</label>

                  <div class="col-sm-9">
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control pull-right" id="nyuin" name="daterange" required>
                      <span class="input-group-btn">
                        <button type="submit" class="btn btn-info btn-flat">登録</button>
                      </span>
                    </div>
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
              </form>
            </div>
            <!-- /.box-body -->
            <?php } else { ?>
            <a class="btn btn-success btn-block" href="<?= site_url("calendar/ukeban/{$shoken['id']}") ?>" role="button">
              <i class="fa fa-envelope-o margin-r-5"></i>受付番号 登録
            </a>
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
<script src="/vendor/tippy/popper.js@1"></script>
<script src="/vendor/tippy/tippy.js@5"></script>
<!-- iCheck 1.0.1 -->
<script src="/vendor/adminlte-2.4.18/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
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
              events: <?= \App\Libraries\Smartcare::toJsonEvents($paypay, ['ukeban_id' => $ukeban_id]) ?>,
              color: "#00a65a",
              description: "通院",
          },
          {
              events: <?= \App\Libraries\Smartcare::toJsonEvents($no_pay, ['ukeban_id' => $ukeban_id]) ?>,
              color: "#ffaaaa",
              description: "支払えない通院",
          },
          {
              events: <?= \App\Libraries\Smartcare::toJsonEvents(\App\Libraries\Smartcare::conbineNyuin($shoken['nyuin']), ['ukeban_id' => $ukeban_id]) ?>,
              color: "#00c0ef",
              description: "入院",
          },
          {
              events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['shujutsu'], ['ukeban_id' => $ukeban_id]) ?>,
              color: "#dd4b39",
              description: "手術",
          },
          {
              events: <?= \App\Libraries\Smartcare::toJsonEvents($shoken['bunsho'], ['ukeban_id' => $ukeban_id]) ?>,
              color: "#d2d6de",
              description: "文書",
          },
      ];
      eventData.forEach(function(events){
          events.events.forEach(function(event){
              start = event.start.split('-');
              end = event.end.split('-');

              start = new Date(start[0], start[1]-1, start[2]);
              end = new Date(end[0], end[1]-1, end[2]);

              while (start < end) {
                  if (events.rendering) {
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).css("text-decoration", 'underline');
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).css("font-weight", 'bold');
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).css("color", 'green');
                  } else {
                      color = event.color ? event.color : events.color;
                      if (events.color == "#dd4b39") { // 手術
                          $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).css("font-weight", 'bold');
                          $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).css("color", 'red');
                      } else {
                          $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).css("background-color", color);
                      }
                  }

                  description = event.description ? event.description : events.description;
                  if (description) {
                      title = $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).data('title');
                      if (!title) title = [];
                      title.push(description);
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).data('title', title);
                      $("#day-"+$.datepicker.formatDate("yy-m-dd", start)).prop('title', title.join(', '));
                  }
                  // 通院の時通院数をカウントアップ
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
              });
          },
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
      });

      //Date range picker
      $('#nyuin').daterangepicker({
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
      $('#monthrange').monthrangepicker({
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

      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
          checkboxClass: 'icheckbox_flat-green',
          radioClass   : 'iradio_flat-green'
      });

      $('#no-data').on('ifChanged', function(){
          nenview();
      });
  });

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
      $('#collapseOne').collapse('hide');
      $('#collapseTwo').collapse('show');
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
