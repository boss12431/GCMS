<?php
/**
 * @filesource modules/personnel/controllers/admin/category.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Personnel\Admin\Category;

use \Kotchasan\Http\Request;
use \Kotchasan\Html;
use \Gcms\Login;
use \Gcms\Gcms;
use \Kotchasan\Language;

/**
 * module=personnel-category
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{

  /**
   * แสดงรายการหมวดหมู่
   *
   * @param Request $request
   * @return string
   */
  public function render(Request $request)
  {
    // ข้อความ title bar
    $this->title = Language::trans('{LNG_List of} {LNG_Personnel groups}');
    // เลือกเมนู
    $this->menu = 'modules';
    // อ่านข้อมูลโมดูล และ config
    $index = \Index\Adminmodule\Model::getModuleWithConfig('personnel', $request->request('mid')->toInt());
    // admin
    $login = Login::adminAccess();
    // can_config หรือ สมาชิกตัวอย่าง
    if ($index && $login && (Gcms::canConfig($login, $index, 'can_config') || !Login::notDemoMode($login))) {
      // แสดงผล
      $section = Html::create('section');
      // breadcrumbs
      $breadcrumbs = $section->add('div', array(
        'class' => 'breadcrumbs'
      ));
      $ul = $breadcrumbs->add('ul');
      $ul->appendChild('<li><span class="icon-customer">{LNG_Module}</span></li>');
      $ul->appendChild('<li><a href="{BACKURL?module=personnel-settings&mid='.$index->module_id.'}">'.ucfirst($index->module).'</a></li>');
      $ul->appendChild('<li><span>{LNG_Personnel groups}</span></li>');
      $section->add('header', array(
        'innerHTML' => '<h2 class="icon-group">'.$this->title.'</h2>'
      ));
      // แสดงตาราง
      $section->appendChild(createClass('Personnel\Admin\Category\View')->render($request, $index));
      return $section->render();
    }
    // 404.html
    return \Index\Error\Controller::page404();
  }
}