<?php

function zerowp_monitor($echo = true)
{
    if (!is_user_logged_in()) {
        return '';
    }

    global $zwp_mon;

    ob_start(); ?>
    <div class="zerowp-monitor">
            <pre>
                <?php print_r($zwp_mon); ?>
<!--                --><?php //var_dump($zwp_mon); ?>
            </pre>
    </div>
    <?php
    $monitor = ob_get_clean();
    if ($echo) {
        echo $monitor;
    } else {
        echo $monitor;
    }
    return '';
}

function z_mon($data, $id = 'hjkjhh')
{
    global $zwp_mon;

    if ($id === 'hjkjhh') {
        $zwp_mon[] = $data;
    } else {
        $zwp_mon[$id] = $data;
    }
}