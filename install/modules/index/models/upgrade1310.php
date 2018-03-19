<?php
/**
 * @filesource modules/index/models/upgrade1310.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Upgrade1310;

/**
 * อัปเกรด
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Index\Upgrade\Model
{

  /**
   * อัปเกรดเป็นเวอร์ชั่น 13.1.0
   *
   * @return string
   */
  public static function upgrade($db)
  {
    return (object)array(
        'content' => '<li class="correct">Upgrade to Version <b>13.1.0</b> complete.</li>',
        'version' => '13.1.0'
    );
  }
}