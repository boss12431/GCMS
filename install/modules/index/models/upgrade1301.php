<?php
/**
 * @filesource modules/index/models/upgrade1301.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Upgrade1301;

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
   * อัปเกรดเป็นเวอร์ชั่น 13.0.1
   *
   * @return string
   */
  public static function upgrade($db)
  {
    return (object)array(
        'content' => '<li class="correct">Upgrade to Version <b>13.0.1</b> complete.</li>',
        'version' => '13.0.1'
    );
  }
}