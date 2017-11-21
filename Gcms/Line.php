<?php
/**
 * @filesource Gcms/Line.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Gcms;

use \Kotchasan\Curl;

/**
 *  LINE Notify
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Line extends \Kotchasan\KBase
{

  /**
   * เมธอดส่งข้อความไปยังไลน์
   *
   * @param string $message ข้อความที่จะส่ง
   * @return string คืนค่าข้อความว่างถ้าสำเร็จ หรือ คืนค่าข้อความผิดพลาด
   */
  public static function send($message)
  {
    if ($message != '' && !empty(self::$cfg->line_api_key)) {
      $ch = new Curl;
      $ch->setHeaders(array(
        'Authorization' => 'Bearer '.self::$cfg->line_api_key
      ));
      $result = $ch->post('https://notify-api.line.me/api/notify', array('message' => $message));
      if ($ch->error()) {
        return $ch->errorMessage();
      } else {
        $result = json_decode($result, true);
        if ($result['status'] != 200) {
          return $result['message'];
        }
      }
    }
    return '';
  }
}