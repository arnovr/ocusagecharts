$(document).ready(function(){
    $( "select[name*='ocusagecharts-charts']" ).change(function(){
        OC.msg.startSaving('#ocusagecharts-msg');
        var post = $(this).serialize();
        $.post(OC.filePath('ocusagecharts', '', 'savepersonal.php'), post, function(data){
            OC.msg.finishedSaving('#ocusagecharts-msg', data);
        });
    });
});