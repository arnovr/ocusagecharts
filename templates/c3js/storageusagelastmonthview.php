<div id="controls" style="margin-left: 3px;">
    <div id="new" class="button">
        <a href="javascript:showChart('kb');"><?php p($l->t('Kilobytes')); ?></a>
    </div>
    <div id="new" class="button">
        <a href="javascript:showChart('mb');"><?php p($l->t('Megabytes')); ?></a>
    </div>
    <div id="new" class="button">
        <a href="javascript:showChart('gb');"><?php p($l->t('Gigabytes')); ?></a>
    </div>
</div>
<div style="height: 50px;"></div>
<h1><?php p($l->t($_['chart']->getConfig()->getChartType())); ?></h1>
<?php
echo '<div class="chart" id="chart_' . $_['chart']->getConfig()->getId() . '"><div class="icon-loading" style="height: 60px;"></div></div>';
?>
<script type="application/javascript">
    function showChart(given)
    {
        if ( given == 'kb' ) {
            label = '<?php p($l->t('Kilobytes')); ?>';
        }
        if ( given == 'mb' ) {
            label = '<?php p($l->t('Megabytes')); ?>';
        }
        if ( given == 'gb' ) {
            label = '<?php p($l->t('Gigabytes')); ?>';
        }

        var url = '<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->getId(), 'requesttoken' => $_['requesttoken'])); ?>';
        url += '&size=' + given;
        c3.generate({
            bindto: '#chart_<?php p($_['chart']->getConfig()->getId());?>',
            data: {
                x: 'x',
                mimeType: 'json',
                url: url
            },
            axis: {
                x: {
                    type: 'timeseries',
                    tick: {
                        format: '%Y-%m-%d'
                    }
                },
                y: {
                    label: label
                }
            }
        });
    }
    showChart('mb');
</script>