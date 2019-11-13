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
                年間カレンダー (未実装)
              </a>
            </h4>

            <div class="box-tools pull-right">
              <div class="input-group input-group-sm" style="width:200px;">
                <input type="text" class="form-control" id="monthrange" name="monthrange" value="<?= date('Y/m', strtotime($shoken['date'])) ?> - <?= date('Y/m') ?>" onchange="nenview(this.value)">
                <span class="input-group-btn">
                  <button type="button" class="btn btn-info btn-flat" onclick="$('#monthrange').val('<?= date('Y/m', strtotime($shoken['date'])) ?> - <?= date('Y/m') ?>').change()">解除</button>
                </span>
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
                月間カレンダー (未実装)
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
          <li class="active"><a href="#activity" data-toggle="tab">通院数 (未実装)</a></li>
          <li><a href="#timeline" data-toggle="tab">受付番号</a></li>
          <li><a href="#settings" data-toggle="tab">カレンダー</a></li>
        </ul>
        <div class="tab-content">
          <div class="active tab-pane" id="activity">

            <strong><i class="fa fa-hotel margin-r-5"></i>入院：2018/08/05</strong>
            <p>4日間 (8/12 8/15 8/20 9/10)</p>

            <strong><i class="fa fa-calendar-plus-o margin-r-5"></i>手術：2018/08/05</strong>
            <p>0日間</p>

          </div>
          <!-- /.tab-pane -->
          <div class="tab-pane" id="timeline">
            <!-- The timeline -->
            <ul class="timeline timeline-inverse">

              <!-- timeline time label -->
              <li class="time-label">
                <a class="btn-warning btn btn-block" href="<?= site_url("calendar/edit/{$shoken['id']}/") ?>" role="button">
                  <i class="fa fa-pencil margin-r-5"></i>契約日 (<?= $shoken['date'] ?>)
                </a>
              </li>
              <!-- /.timeline-label -->
              <?php foreach($ukeban as $line){ ?>
              <!-- timeline item -->
              <li>
                <i class="fa fa-envelope bg-blue"></i>
                <div class="timeline-item">
                  <span class="time"><i class="fa fa-clock-o"></i> <?= $line['date'] ?></span>
                  <h3 class="timeline-header"><?= $line['id'] ?></h3>
                  <div class="timeline-body">
                    <?php if(isset($nyuin[$line['id']])){ ?>
                    <i class="fa fa-hotel margin-r-5"></i>入院：
                    <p>
                      <?php foreach($nyuin[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['start']) ?> -
                      <?= str_replace('-', '/', $i['end']) ?><br />
                      <?php } ?>
                    </p>
                    <?php } ?>
                    <?php if(isset($shujutsu[$line['id']])){ ?>
                    <i class="fa fa-calendar-plus-o margin-r-5"></i>手術：
                    <p>
                      <?php foreach($shujutsu[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['date']) ?><br />
                      <?php } ?>
                    </p>
                    <?php } ?>
                    <?php if(isset($tsuin[$line['id']])){ ?>
                    <i class="fa fa-stethoscope margin-r-5"></i>通院：
                    <p>
                      <?php foreach($tsuin[$line['id']] as $i){ ?>
                      <?= str_replace('-', '/', $i['date']) ?><br />
                      <?php } ?>
                    </p>
                    <?php } ?>
                  </div>
                  <!--
                  <div class="timeline-footer">
                    <a class="btn btn-primary btn-xs">結果表示</a>
                    <a class="btn btn-danger btn-xs">これだけ</a>
                  </div>
                  -->
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

          <div class="tab-pane" id="settings">
            <?php if(isset($line)){ ?>
            <div class="box-header with-border">
              <h3 class="box-title"><?= $line['id'] ?> (最新)</h3>
            </div>
            <div class="box-body">
              <form class="form-horizontal" action="<?= site_url("api/v1/shoken/{$shoken['id']}/ukeban/{$line['id']}/tsuin") ?>" method="POST">
                <!-- Date -->
                <div class="form-group">
                  <label for="tsuin" class="col-sm-3 control-label"><i class="fa fa-stethoscope margin-r-5"></i>通院</label>

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
                  <label for="shujutsu" class="col-sm-3 control-label"><i class="fa fa-calendar-plus-o margin-r-5"></i>手術</label>

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

<script>
  $(function () {
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
          events    : [
              {
                  title          : '通院',
                  start          : "2019-10-10",
                  end            : "2019-10-10",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
              {
                  title          : '通院',
                  start          : "2019-10-17",
                  end            : "2019-10-17",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
              {
                  title          : '通院',
                  start          : "2019-10-24",
                  end            : "2019-10-24",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
              {
                  title          : '手術',
                  start          : "2019-10-15",
                  end            : "2019-10-15",
                  backgroundColor: '#f39c12', //yellow
                  borderColor    : '#f39c12' //yellow
              },
              {
                  title          : '入院',
                  start          : "2019-10-15",
                  end            : "2019-10-20",
                  backgroundColor: '#00c0ef', //Info (aqua)
                  borderColor    : '#00c0ef' //Info (aqua)
              },
              {
                  title          : '通院',
                  start          : "2019-10-30",
                  end            : "2019-10-30",
                  backgroundColor: '#00a65a', //Success (green)
                  borderColor    : '#00a65a' //Success (green)
              },
          ],
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
      })

      //Date picker
      $('#monthrange').monthrangepicker({
          autoApply: true,
          drops: 'down',
          minDate: '<?= $shoken['date'] ?>',
          maxDate: '<?= date('Y/m') ?>',
          locale: {
              format:'YYYY/MM',
              applyLabel: '反映',
              cancelLabel: '取消',
              monthNames: ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月']
          }
      });

  });

  function nenview(range) {
      s_range = range.split(' - ');
      start = s_range[0].split('/');
      start = new Date(start[0], start[1]);
      end = s_range[1].split('/');
      end = new Date(end[0], end[1]);

      $('#nenview tr').each(
          function(index, element){
              line = $(element).attr('id').split('-');
              line = new Date(line[1], line[2])
              console.log(start + ':' + line + ':' + end);
              if (start <= line && line <= end) {
                  console.log('show');
                  $(element).show();
              } else {
                  console.log('hide');
                  $(element).hide();
              }
          });
  }
</script>
<?= $this->endSection() ?>
