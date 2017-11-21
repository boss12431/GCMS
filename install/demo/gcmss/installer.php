<?php
/**
 * @filesource gcmss/installer.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Gcmss\Installer;

/**
 * เว็บไซต์โรงเรียนหรือ อบต
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  public static function import($db)
  {
    self::$cfg->skin = 'gts';
  }
}