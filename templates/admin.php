<?php
use OCA\ocUsageCharts\AppInfo\Chart;
echo '
<div class="section" id="ocusagecharts-personal">
	<h2>';
p($l->t('ApiSettings_title'));
echo '
</h2>
<div>
    <div id="ocusagecharts-msg"></div>
<br />
    ';
$app = new Chart();
$container = $app->getContainer();
$user = $container->query('OwncloudUser');
$appConfig = $container->query('ServerContainer')->getConfig();

echo '<form id="apisettings">';
p($l->t('url'));
echo ': <input type="text" name="url" value="' . $appConfig->getAppValue('ocusagecharts', 'url') . '"/>
<p class="inlineblock" id="ocusagecharts-connected">
<span id="connection" class="cronstatus"></span>
</p>
<br />';
p($l->t('username'));
echo ': <input type="text" name="username" value="' . $appConfig->getAppValue('ocusagecharts', 'username') . '"/><br />';
p($l->t('password'));
echo ': <input type="password" name="password" value="' . $appConfig->getAppValue('ocusagecharts', 'password') . '" /><br />';
echo '<input id="apisettings_submit" type="button" value="' . $l->t('Store credentials') . '">';
echo '</form>';
echo '
</div></div>';