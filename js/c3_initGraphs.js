function loadGraph(url, yLabel, shortLable, graphType, format, appendLabel)
{
    if ( format == undefined )
    {
        format = '%Y-%m-%d';
    }
    var appendLabelContent = '';
    if ( appendLabel )
    {
        appendLabelContent = ({
            format: {
                y: function (value) {
                    return value;
                }
            }
        });
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
            labels: appendLabelContent
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
                    return value + ' ' + shortLable;
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
            $(".defaultChart").data("shortlabel"),
            $(".defaultChart").data("type"),
            $(".defaultChart").data("format"),
            false
        );
    }
    if ($(".defaultBar").length > 0 )
    {
        loadGraph(
            $(".defaultBar").data("url"),
            $(".defaultBar").data("label"),
            $(".defaultBar").data("shortlabel"),
            $(".defaultBar").data("type"),
            $(".defaultBar").data("format"),
            true
        );
    }
    if ($(".defaultPie").length > 0 )
    {
        loadPie(
            $(".defaultPie").data("url")
        );
    }
});