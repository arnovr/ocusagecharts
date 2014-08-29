<?php
$url = \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken']));
?>
<div id="controls" style="margin-left: 3px;">
    <div id="new" class="button">
        <a
            data-url="<?php echo $url; ?>"
            data-size="kb"
            data-type="line"
            data-format="%Y-%m-%d"
            data-label="<?php p($l->t('Kilobytes')); ?>"
            href="#"
            >
            <?php p($l->t('Kilobytes')); ?>
        </a>
    </div>
    <div id="new" class="button">
        <a
            id="defaultChart"
            data-url="<?php echo $url; ?>"
            data-size="mb"
            data-type="line"
            data-format="%Y-%m-%d"
            data-label="<?php p($l->t('Megabytes')); ?>"
            href="#"
            >
            <?php p($l->t('Megabytes')); ?>
        </a>
    </div>
    <div id="new" class="button">
        <a
            data-url="<?php echo $url; ?>"
            data-size="gb"
            data-type="line"
            data-format="%Y-%m-%d"
            data-label="<?php p($l->t('Gigabytes')); ?>"
            href="#"
            >
            <?php p($l->t('Gigabytes')); ?>
        </a>
    </div>
</div>
<div style="height: 50px;"></div>
<h1><?php p($l->t($_['chart']->getConfig()->getChartType())); ?></h1>
<?php
echo '<div class="chart" id="chart"><div class="icon-loading" style="height: 60px;"></div></div>';
?>