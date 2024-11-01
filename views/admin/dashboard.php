<?php 


namespace WPWebVitalsTracking;

if (!defined('ABSPATH')) {
	exit;
}

$ranges = Records::instance()->getRanges();

?>
<div class="wrap">
    <h1><?= esc_html__('Web Vitals Tracking - Dasbhoard', 'webvitals-tracking') ?></h1>
    <p>
        <b><?= esc_html__('What is this dashboard?', 'webvitals-tracking') ?></b><br />
        <?= esc_html__('This dashboard contains summary of your website core web vitals signals directly from users visiting your website.', 'webvitals-tracking') ?>
        <?= sprintf(
                esc_html__('Have any questions? See %1$sFAQ%2$s.', 'webvitals-tracking'),
                '<a href="#faq">',
                '</a>') ?>
    </p>

    <div class="flex-row">
        <div class="flex-col" id="mobile">
            <?php $device = 'mobile' ?>
            <h2><?= esc_html__('Mobile', 'webvitals-tracking') ?></h2>

            <?php 
                $avg = Records::instance()->getAVG('mobile', '24 hours ago');
                include('legend.php');
            ?>
            <p class="small"><?= esc_html__('Average from last 24 hours', 'webvitals-tracking') ?></p>

            <h3><?= esc_html__('Last 24h summary', 'webvitals-tracking') ?></h3>
            
            <?php
                // get all records
                $records = Records::instance()->get([
                    'device' => $device,
                    'date' => date('Y-m-d H:i:s', strtotime('24 hours ago'))
                ], 'day', 'date', 'ASC', 0, 1000);
                
                if ($records && count($records)) {
                    include('graph.php');
                    include('table.php');
                } else {
                    echo '<p style="text-align:center">';
                    echo esc_html__('Not enough data', 'webvitals-tracking');
                    echo '</p>';
                }
            ?>
            
            <h3><?= esc_html__('Last month summary', 'webvitals-tracking') ?></h3>
            
            <?php
                // get all records
                $records = Records::instance()->get([
                    'device' => $device,
                    'date' => date('Y-m-d H:i:s', strtotime('1 month ago'))
                ], 'month', 'date', 'ASC', 0, 1000);
                
                if ($records && count($records)) {
                    include('graph.php');
                    include('table.php');
                } else {
                    echo '<p>';
                    esc_html__('Not enough data', 'webvitals-tracking');
                    echo '</p>';
                }
            ?>

            <?php include('worsts.php') ?>

        </div>

        <div class="flex-col" id="desktop">
            <?php $device = 'desktop' ?>
            <h2><?= esc_html__('Desktop', 'webvitals-tracking') ?></h2>

            <?php 
                $avg = Records::instance()->getAVG('desktop', '24 hours ago');
                include('legend.php');
            ?>
            <p class="small"><?= esc_html__('Average from last 24 hours', 'webvitals-tracking') ?></p>

            <h3><?= esc_html__('Last 24h summary', 'webvitals-tracking') ?></h3>

            <?php
                // get all records
                $records = Records::instance()->get([
                    'device' => $device,
                    'date' => date('Y-m-d H:i:s', strtotime('24 hours ago'))
                ], 'day', 'date', 'ASC', 0, 1000);
                
                if ($records && count($records)) {
                    include('graph.php');
                    include('table.php');
                } else {
                    echo '<p style="text-align:center">';
                    echo esc_html__('Not enough data', 'webvitals-tracking');
                    echo '</p>';
                }
            ?>

            <h3><?= esc_html__('Last month summary', 'webvitals-tracking') ?></h3>

            <?php
                // get all records
                $records = Records::instance()->get([
                    'device' => $device,
                    'date' => date('Y-m-d H:i:s', strtotime('1 month ago'))
                ], 'month', 'date', 'ASC', 0, 1000);
                
                if ($records && count($records)) {
                    include('graph.php');
                    include('table.php');
                } else {
                    echo '<p>';
                    esc_html__('Not enough data', 'webvitals-tracking');
                    echo '</p>';
                }
            ?>

            <?php include('worsts.php') ?>

        </div>
    </div>

    <div id="faq">
        <h3><?= esc_html__('FAQ', 'webvitals-tracking') ?></h3>
        <p>
            <b><?= esc_html__('What is Core Web Vitals?', 'webvitals-tracking') ?></b><br />
            <span class="answer">
                <?= sprintf(
                        esc_html__('From the %1$sofficial page%2$s Web Vitals is:%3$s
                            an initiative by Google to provide [...] quality signals that are essential to delivering 
                            a great user experience on the web.
                            [...] Core Web Vitals are the subset of Web Vitals that apply to all web pages, should be measured by all site owners, 
                            and will be surfaced across all Google tools. %3$s
                            Each of the Core Web Vitals represents a distinct facet of the user experience, is measurable in the field, 
                            and reflects the real-world experience of a critical user-centric outcome.', 'webvitals-tracking'),
                        '<a href="https://web.dev/vitals/" target="_blank" rel="noreferrer noopener">',
                        '</a>',
                        '<br />'
                    ) ?>
            </span>
        </p>
        <p>
            <b><?= esc_html__('Why are them important?', 'webvitals-tracking') ?></b><br />
            <span class="answer">
                <?= sprintf(
                        esc_html__('They are important not only to improve user experience on websites but also because since 2021 Core Web Vitals %1$swill become official ranking signals for Google%2$s.', 'webvitals-tracking'),
                        '<a href="https://webmasters.googleblog.com/2020/05/evaluating-page-experience.html" target="_blank" rel="noreferrer noopener">',
                        '</a>'
                    ) ?>
            </span>
        </p>
        
        <p>
            <b><?= esc_html__('Which are Core Web Vitals?', 'webvitals-tracking') ?></b><br />
            <ul id="cwv-desc">
                <li>
                    <b><?= esc_html__('Largest Contentful Paint (LCP)', 'webvitals-tracking') ?></b>
                    <p>
                        <?= esc_html__('Measures loading performance.', 'webvitals-tracking') ?><br />
                        <?= esc_html__('To provide a good user experience, LCP should occur within 2.5 seconds of when the page first starts loading.', 'webvitals-tracking') ?></p>
                </li>
                <li>
                    <b><?= esc_html__('First Input Delay (FID)', 'webvitals-tracking') ?></b>
                    <p>
                        <?= esc_html__('Measures interactivity.', 'webvitals-tracking') ?><br />
                        <?= esc_html__('To provide a good user experience, pages should have a FID of less than 100 milliseconds.', 'webvitals-tracking') ?></p>
                </li>
                <li>
                    <b><?= esc_html__('Cumulative Layout Shift (CLS)', 'webvitals-tracking') ?></b>
                    <p>
                        <?= esc_html__('Measures visual stability.', 'webvitals-tracking') ?><br />
                        <?= esc_html__('To provide a good user experience, pages should maintain a CLS of less than 0.1.', 'webvitals-tracking') ?></p>
                </li>
            </ul>
        </p>

        <p>
            <b><?= esc_html__('Why do I not see any data?', 'webvitals-tracking') ?></b><br />
            <span class="answer">
                <?= esc_html__('Data will be generated by users with Javascript enabled navigating your website. Not all visits generate all metrics signal and not all browser can generate this data, please read more about browser compatibility in the following FAQ', 'webvitals-tracking') ?>
            </span>
        </p>
        <p>
            <b><?= esc_html__('What is web vitals browser compatibility?', 'webvitals-tracking') ?></b><br />
            <span class="answer">
                <?= esc_html__('Some Web Vitals metrics are, at the moment, only supported by Chromium based browser (e.g. Chrome, Edge, Opera, Samsung Internet)', 'webvitals-tracking') ?>
                <br />
                <?= sprintf(
                        esc_html__('These browser cover more than 70%% of global users (stats as for May 2020 by %1$sw3counter%2$s)', 'webvitals-tracking'),
                        '<a href="https://www.w3counter.com/globalstats.php" target="_blank" rel="noreferrer noopener">',
                        '</a>'
                    ) ?>
            </span>
        </p>
        <p>
            <b><?= esc_html__('Why data is not continuos, for example missing points and cells are empty?', 'webvitals-tracking') ?></b><br />
            <span class="answer">
                <?= esc_html__('As mentioned above data will be generated by users with compatible browsers, 
                                if your website does not have enough traffic some metric may be missing in reports.', 'webvitals-tracking') ?>
                <br />
                <?= esc_html__('If you think this is not the problem there may be conflicts with other plugins or with the theme.', 'webvitals-tracking') ?>
            </span>
        </p>
    </div>
    <br /><br />
    <p>
        <b><?= esc_html__('Disclaimer:', 'webvitals-tracking') ?></b>
        <br /><?= esc_html__('Not all page view generate all metric reports, if your website doesn\'t have enough traffic
                                some metric may be not accurate or may be missing.', 'webvitals-tracking') ?>
        <br /><?= esc_html__('Some Web Vitals metrics are, at the moment, only supported by Chromium based browser 
                                (e.g. Chrome, Edge, Opera, Samsung Internet).', 'webvitals-tracking') ?>
        <br /><a href="https://web.dev/vitals/" target="_blank" rel="noreferrer noopener">
                <?= esc_html__('For reference see official Web Vitals', 'webvitals-tracking') ?>
            </a>
    </p>

</div>
