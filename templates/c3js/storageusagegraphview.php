<script type="application/javascript">
    var chart<?php p($_['chart']->getConfig()->chartId);?> = c3.generate({
        bindto: '#chart_<?php p($_['chart']->getConfig()->chartId);?>',
        data: {
            x: 'x',
            mimeType: 'json',
            url: '<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart.load_chart', array('id' => $_['chart']->getConfig()->chartId));?>'
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    format: '%Y-%m-%d'
                }
            }
        }
    });
</script>