function loadGraph(url, yLabel, graphType, format)
{
    if ( format == undefined )
    {
        format = '%Y-%m-%d';
    }

    c3.generate({
        bindto: '#chart',
        size: {
            height: 520
        },
        data: {
            x: 'x',
            mimeType: 'json',
            url: url ,
            type: graphType,
            labels: {
                format: {
                    y: d3.format("$,")
                }
            }
        },
        axis: {
            x: {
                type: 'timeseries',
                tick: {
                    format: format
                }
            },
            y: {
                label: yLabel
            }
        },
        tooltip: {
            format: {
                value: function (value) {
                    return value + yLabel;
                }
            }
        }
    });
}

function loadPie(url)
{
    c3.generate({
        bindto: '#chart',
        size: {
            height: 520
        },
        data: {
            mimeType: 'json',
            url: url,
            type: 'pie'
        }
    });
}
$( document ).ready(function() {
    if ($(".defaultChart").length > 0 )
    {
        loadGraph(
            $(".defaultChart").data("url"),
            $(".defaultChart").data("label"),
            $(".defaultChart").data("type"),
            $(".defaultChart").data("format")
        );
    }
    if ($(".defaultBar").length > 0 )
    {
        loadGraph(
            $(".defaultBar").data("url"),
            $(".defaultBar").data("label"),
            $(".defaultBar").data("type"),
            $(".defaultBar").data("format")
        );
    }
    if ($(".defaultPie").length > 0 )
    {
        loadPie(
            $(".defaultBar").data("url")
        );
    }
});