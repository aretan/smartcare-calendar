<?php foreach ($shoken as $value) { ?>
<li <?=(strpos(current_url(), site_url($value['id'])) === 0) ? 'class="active"' : '' ?>>
  <a href="<?=site_url("{$value['id']}/") ?>">
    <span><?=$value['name'] ?></span>
    <span class="pull-right-container">
      <span class="label label-success pull-right"><?=$value['id'] ?></span>
    </span>
  </a>
</li>
<?php } ?>
