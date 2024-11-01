<?php 


namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

// show worst top 10 for each metric
if (is_array($lines)) {
    foreach($lines as $name => $d) {
        if (!isset($ranges[$name])) continue;

        $treshold = $ranges[$name][0][1];
        // LCP is saved in milliseconds, but ranged and displayed as second
        if ($name == 'LCP') $treshold *= 1000;
        $worst = Records::instance()->getByPaths([
            'device' => $device,
            'name' => $name,
            'value' => $treshold,
            'value_operator' => '>'
        ], 0, 10);

        if ($worst) {
            ?>
                <h3><?= htmlentities(sprintf(__('%s Worst url - Top 10', 'webvitals-tracking'), $name)) ?></h3>
                <table id="worst-table">
                    <thead>
                        <tr>
                            <th><?= htmlentities(__('Url', 'webvitals-tracking')) ?></th>
                            <th><?= htmlentities(__('Metric', 'webvitals-tracking')) ?></th>
                            <th><?= htmlentities(__('Score', 'webvitals-tracking')) ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($worst as $w) { ?>
                            <tr>
                                <td><?= htmlentities($w->path) ?></td>
                                <td><?= htmlentities($name) ?></td>
                                <?php 
                                    $value = Record::formatValue($name, $w->avg);
                                ?>
                                <td style="background: <?= Record::getValueColor($name, $value) ?>"><?= $value ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php
        }
    }
}
