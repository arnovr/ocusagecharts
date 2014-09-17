<?php
$userSelected = 'gb'; // @TODO this should go from the appconfigservice
$selected = ' selected="selected"';

echo '
<div class="section" id="ocusagecharts-personal">
	<h2>';
	p($l->t('DefaultChartSize'));
    echo '</h2>
<div>

    <label for="ocusagecharts-default-size">';
    p($l->t('DefaultChartSize'));
    echo '</label>

    <select id="ocusagecharts-default-size">
        <option name="kb"' . ($userSelected == 'kb' ? $selected: '' ) . '>' . $l->t('Kilobytes') . '</option>
        <option name="mb"' . ($userSelected == 'mb' ? $selected: '' ) . '>' . $l->t('Megabytes') . '</option>
        <option name="gb"' . ($userSelected == 'gb' ? $selected: '' ) . '>' . $l->t('Gigabytes') . '</option>
        <option name="tb"' . ($userSelected == 'tb' ? $selected: '' ) . '>' . $l->t('Terabytes') . '</option>
    </select>
</div>
</div>
';
