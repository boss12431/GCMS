<?php
/**
 * @filesource modules/personnel/models/admin/setup.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Personnel\Admin\Setup;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;
use \Gcms\Gcms;

/**
 * โมเดลสำหรับแสดงรายการบทความ (setup.php)
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Orm\Field
{
  /**
   * ชื่อตาราง
   *
   * @var string
   */
  protected $table = 'personnel';

  /**
   * รับค่าจาก action ของ table (setup.php)
   *
   * @param Request $request
   */
  public static function action(Request $request)
  {
    $ret = array();
    // session, referer, member, ไม่ใช่สมาชิกตัวอย่าง
    if ($request->initSession() && $request->isReferer() && $login = Login::adminAccess()) {
      if (Login::notDemoMode($login)) {
        // รับค่าจากการ POST
        $id = $request->post('id')->toString();
        $action = $request->post('action')->toString();
        // อ่านข้อมูลโมดูล และ config
        $index = \Index\Adminmodule\Model::getModuleWithConfig('personnel', $request->post('mid')->toInt());
        if ($index && Gcms::canConfig($login, $index, 'can_write') && preg_match('/^[0-9,]+$/', $id)) {
          $module_id = (int)$index->module_id;
          // Model
          $model = new \Kotchasan\Model;
          if ($action === 'delete') {
            // ลบ
            $id = explode(',', $id);
            $query = $model->db()->createQuery()->select('picture')->from('personnel')->where(array(array('id', $id), array('module_id', $module_id)))->toArray();
            foreach ($query->execute() as $item) {
              // ลบไฟล์
              @unlink(ROOT_PATH.DATA_FOLDER.'personnel/'.$item['picture']);
            }
            // ลบข้อมูล
            $model->db()->createQuery()->delete('personnel', array(array('id', $id), array('module_id', $module_id)))->execute();
            // reload
            $ret['location'] = 'reload';
          } else if ($action === 'order') {
            // update order
            $model->db()->update($model->getTableName('personnel'), array(
              array('id', (int)$id),
              array('module_id', $module_id)
              ), array('order' => $request->post('value')->toInt()));
          }
        }
      }
    }
    if (empty($ret)) {
      $ret['alert'] = Language::get('Unable to complete the transaction');
    }
    // คืนค่าเป็น JSON
    echo json_encode($ret);
  }
}
