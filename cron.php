<?php
/**
 * cron.php
 * หน้าเพจสำหรับให้ Cron เรียกใช้.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */
// load Kotchasan
include 'load.php';
// สำหรับบอกว่ามาจากการเรียกโดย cron
define('MAIN_INIT', 'cron');
// Initial Kotchasan Framework
$app = Kotchasan::createWebApplication('Gcms\Config');
$app->defaultController = 'Index\Cron\Controller';
$app->run();
