<?php 


namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

// NOTE: require $lines set

?>

<table id="table-vitals">
    <thead>
        <tr>
            <th><?= htmlentities(__('Date', 'webvitals-tracking'))?></th>
            <?php 
                foreach($lines as $name => $d) {
                    echo '<th>'. htmlentities($name) .'</th>';
                }
            ?>
        </tr>
    </thead>
    <tbody>
        <?php 
            $i = 0;
            foreach($dates as $date) { ?>
            <tr>
                <td><?= htmlentities($date)?></th>
                <?php 
                    foreach($lines as $name => $d) {
                        if (!isset($d[$i])) {
                            echo '<td>&nbsp;</td>';
                            continue;
                        }
                        
                        echo '<td style="background: '.Record::getValueColor($name, $d[$i]).'">';
                        
                        echo htmlentities($d[$i]) .'</td>';
                    }
                ?>
            </tr>
            <?php 
                $i++;
            } ?>
    </tbody>
</table>
