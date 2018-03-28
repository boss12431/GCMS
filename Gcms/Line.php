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
   * @param string $line_api_key
   * @return string คืนค่าข้อความว่างถ้าสำเร็จ หรือ คืนค่าข้อความผิดพลาด
   */
  public static function send($message, $line_api_key = null)
  {
    if ($line_api_key == '') {
      $line_api_key = empty(self::$cfg->line_api_key) ? '' : self::$cfg->line_api_key;
    }
    if ($line_api_key == '') {
      return 'API key can not be empty';
    } elseif ($message == '') {
      return 'message can not be blank';
    } else {
      $ch = new Curl;
      $ch->setHeaders(array(
        'Authorization' => 'Bearer '.$line_api_key
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