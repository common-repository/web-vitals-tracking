<?php 


namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="legend">
    <?php foreach($ranges as $name => $range_list) { ?>
        <div class="leg">
            <div class="name">
                <span><?= $name ?></span>
                <?php if(isset($avg[$name])) { 
                    $value = Record::formatValue($name, $avg[$name]); ?>
                    <br />
                    <span class="avg-val" style="color: <?php
                        echo Record::getValueColor($name, $value, 'inherit');
                      ?>"><?= $value ?></span>
                <?php } ?>
            </div>
            <div class="ranges">
                <?php foreach($range_list as $range) { ?>
                    <div class="range" style="background-color: <?=$range['color']?>">
                        <span class="value"><?= __('from', 'webvitals-tracking') .' '. $range[0] ?> <?= (isset($range[1]) ? (__('to', 'webvitals-tracking') . ' ' . $range[1]) : '') ?></span>
                        <span class="label"><?= $range['label'] ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>
