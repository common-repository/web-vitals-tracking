<?php 

namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

// NOTE: require $records set

$unique_key = rand(1,10000);
$hide_ranges_not_reached = true;

// parse records for charts
$fallback_dates = [];
$dates = [];
$date_min = null;
$date_max = null;
$lines = [];

$annotations = [];
$greatests = [];

$colors = ['#6af', '#1b8e00', '#ffa84b'];

// calculate dates
foreach ($records as $r) {
    if ($date_min === null || $date_min > $r->date) $date_min = $r->date;
    if ($date_max === null || $date_max < $r->date) $date_max = $r->date;
    if (!in_array($r->date, $fallback_dates)) $fallback_dates[] = $r->date;
}

// calculate all range from min to max
$dates[] = $date_min;
if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $date_min)) {
    // contains minutes, increase it by 1 minute
    $interval = 'PT1M';
    $format = 'Y-m-d H:i';
} elseif (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}$/', $date_min)) {
    // contains hours, increase it by 1 hour
    $interval = 'PT1H';
    $format = 'Y-m-d H';
} else {
    // contains hours, increase it by 1 day
    $interval = 'P1D';
    $format = 'Y-m-d';
}

// fill $dates with each step date
while ($date_min < $date_max) {
    // increment date_min by one unit
    if ($format == 'Y-m-d H') {
        $d = new \DateTime($date_min . ':00');
    } else {
        $d = new \DateTime($date_min);
    }
    if (!$d) {
        // not a supported format, use fallback array for x axis
        $dates = $fallback_dates;
        break;
    }
    
    $d->add(new \DateInterval($interval));
    $date_min = $d->format($format);
    $dates[] = $date_min;
}

// prepare data for chart
foreach ($records as $r) {
    if (!isset($lines[$r->name])) {
        $lines[$r->name] = array_fill(0, count($dates), null);
    }
    
    $value = Record::formatValue($r->name, $r->avg);
    
    $date_index = array_search($r->date, $dates);
    $lines[$r->name][$date_index] = $value;
    
    if (!isset($greatests[$r->name]) || $greatests[$r->name] < $value) $greatests[$r->name] = $value;
    
    // print_r($r);
    // echo "<br><br>";
}

// create annotations
foreach($lines as $name => $data) {
    // create annotations
    if (isset($ranges[$name])) {
        $annotations[$name] = [];
        foreach($ranges[$name] as $range) {
            // create this annotation only if there are values higher than this
            if ($hide_ranges_not_reached && isset($greatests[$name]) && $greatests[$name] < $range[0]) continue;

            $annotations[$name][] = [
                'type' => 'line',
                'mode' => 'horizontal',
                'scaleID' => 'y-axis-0',
                'value' => $range[0],
                'borderColor' => $range['color'],
                'borderWidth' => 3,
                'label' => [
                    'enabled' => false,
                    'content' => '↑ ' . $range['label'] . ' ↑'
                ]
            ];
        }
    }
}

$i =0;
foreach($lines as $name => $datas) {
    $i++;
    echo '<canvas class="chart" id="chart_data_'.$unique_key.'_'.htmlentities($name, ENT_QUOTES).'" width="600" data-last="'.($i >= count($lines) ? '1' : '0').'" height="'.($i >= count($lines) ? 150 : 130).'"></canvas>';
}

?>
<script>
    document.addEventListener("DOMContentLoaded", function(){
    <?php 
        $i=0;
        foreach($lines as $name => $datas) { ?>
            (function(){
                var canvas = document.getElementById('chart_data_<?=$unique_key?>_<?=addslashes($name) ?>');
                
                // responsive height
                var w = 1400;
                if (window.innerWidth < w) {
                    var canvH = canvas.getAttribute('height'),
                    calculatedHeight = (1+(((window.innerWidth - w)*(-1))/w)) * canvH;
                    
                    canvas.setAttribute('height', calculatedHeight);
                }
                // responsive width
                var parentW = canvas.parentElement.offsetWidth;
                if (parentW > canvas.getAttribute('width')) {
                    // console.log(canvas.parentElement, parentW);
                    canvas.setAttribute('width', parentW);
                }

                var ctx = canvas.getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: <?= json_encode($dates) ?>,
                        datasets: [{
                            label: '<?= addslashes($name) ?>',
                            backgroundColor: '<?= $colors[$i%(count($colors))] ?>',
                            borderColor: '<?= $colors[$i%(count($colors))] ?>',
                            data: <?= json_encode($datas) ?>,
                            fill: false,
                        }]
                    },
                    options: {
                        annotation: {
                            annotations: <?= json_encode($annotations[$name]) ?>
                        },
                        responsive: window.innerWidth < w,
                        title: {
                            display: false
                        },
                        legend: {
                            display: false
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: <?= $i+1 >= count($lines) ? 'true' : 'false' ?>,
                                    labelString: 'Month'
                                },
                                ticks: {
                                    callback: function(value, index, values) {
                                        if (value.match(/^\d{4}-\d{2}-\d{2} \d{2}/)) {
                                            return value.split(' ')[1] + 'h';
                                        }
                                        return value;
                                    }
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: '<?= addslashes($name) ?>'
                                },
                                afterFit: function(scaleInstance) {
                                    scaleInstance.width = 60; // sets the width to 100px
                                }
                            }]
                        }
                    }
                });
            })();
        <?php $i++;
        } ?>
    });
</script>