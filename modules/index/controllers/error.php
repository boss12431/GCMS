<?php
/**
 * @filesource modules/index/controllers/error.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Error;

use \Kotchasan\Language;
use \Kotchasan\Template;

/**
 * หน้า Error
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Kotchasan\Controller
{

  /**
   * แสดงข้อผิดพลาด (เช่น 404 page not found)
   *
   * @param string $module ชื่อโมดูลที่เรียก
   * @param string $message ข้อความที่จะแสดง ถ้าไม่กำหนดจะใช้ข้อความของระบบ
   * @return object
   */
  public function init($module, $status = 404, $message = '')
  {
    $template = Template::create($module, '', '404');
    $message = Language::get($message == '' ? 'Sorry, cannot find a page called Please check the URL or try the call again.' : $message);
    $template->add(array(
      '/{TOPIC}/' => $message,
      '/{DETAIL}/' => $message
    ));
    $topic = strip_tags($message);
    return (object)array(
        'status' => $status,
        'topic' => $topic,
        'detail' => $template->render(),
        'description' => $topic,
        'keywords' => $topic,
        'module' => $module
    );
  }
}