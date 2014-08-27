
<div id="controls" style="margin-left: 3px;">
    <div id="new" class="button">
        <a href="javascript:showChart('kb');">Kilobytes</a>
    </div>
    <div id="new" class="button">
        <a href="javascript:showChart('mb');">Megabytes</a>
    </div>
    <div id="new" class="button">
        <a href="javascript:showChart('gb');">Gigabytes</a>
    </div>
</div>
<div style="height: 50px;"></div>
<h1>Usage graph</h1>
<?php
echo '<div class="chart" id="chart_' . $_['chart']->getConfig()->chartId . '"><div class="icon-loading" style="height: 60px;"></div></div>';
?>
<script type="application/javascript">
    function showChart(given)
    {
        if ( given == 'kb' ) {
            label = 'Kilobytes';
        }
        if ( given == 'mb' ) {
            label = 'Megabytes';
        }
        if ( given == 'gb' ) {
            label = 'Gigabytes';
        }

        var url = '<?php echo \OCP\Util::linkToRoute('ocusagecharts.chart_api.load_chart', array('id' => $_['chart']->getConfig()->chartId, 'requesttoken' => $_['requesttoken'], 'size' => '')); ?>' + given;
        c3.generate({
            bindto: '#chart_<?php p($_['chart']->getConfig()->chartId);?>',
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