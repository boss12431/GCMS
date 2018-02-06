<?php
/**
 * @filesource Widgets/Search/Controllers/Index.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Widgets\Search\Controllers;

/**
 * Controller หลัก สำหรับแสดงผล Widget
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Index extends \Kotchasan\Controller
{

  /**
   * แสดงผล Widget
   *
   * @param array $query_string ข้อมูลที่ส่งมาจากการเรียก Widget
   * @return string
   */
  public function get($query_string)
  {
    // ฟอร์มค้นหา
    $template = \Kotchasan\Template::createFromFile(ROOT_PATH.'Widgets/Search/Views/search.html');
    $template->add(array(
      '/{ID}/' => uniqid(),
      '/{SEARCH}/' => self::$request->get('q')->topic(),
      '/{MODULE}/' => empty($query_string['module']) ? 'search' : $query_string['module']
    ));
    return $template->render();
  }
}