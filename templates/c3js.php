<div id="chart_<?php p($_['chart']->getGraphType()); ?>">Stub</div>
<script type="application/javascript">
    function generateChart(chartId)
    {
        var chart = c3.generate({
            bindto: chartId,
            data: {
                columns: [
                    ['data1', 30, 200, 100, 400, 150, 250],
                    ['data2', 50, 20, 10, 40, 15, 25]
                ]
            }
        });
    }

    $.ajax({
        url: "test.html",
        context: document.body
    }).done(generateChart("#chart_<?php p($_['chart']->getGraphType()); ?>")
    );
</script>