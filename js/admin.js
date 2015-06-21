$(document).ready(function() {
    $("input[id='apisettings_submit']").click(function () {
        OC.msg.startSaving('#ocusagecharts-msg');
        var post = $("#apisettings").serialize();
        $.post(OC.filePath('ocusagecharts', '', 'saveadmin.php'), post, function (data) {
            OC.msg.finishedSaving('#ocusagecharts-msg', data);

            $.get(OC.filePath('ocusagecharts', '', 'checkconnection.php'), function (data) {
                OC.msg.finishedAction('#connection', data);
            });
        });
    });
});