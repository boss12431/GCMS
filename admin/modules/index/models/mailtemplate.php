<?php
/**
 * @filesource modules/index/models/mailtemplate.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Mailtemplate;

use \Kotchasan\Http\Request;
use \Gcms\Login;
use \Kotchasan\Language;
use \Kotchasan\Orm\Field;

/**
 * ตาราง emailtemplate
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends Field
{

  /**
   * ชื่อตาราง
   *
   * @var string
   */
  protected $table = 'emailtemplate E';

  public function getConfig()
  {
    return array(
      'select' => array(
        'id',
        'email_id',
        'name',
        'language',
        'module',
        'subject'
      )
    );
  }

  /**
   * action
   *
   * @param Request $request
   */
  public static function action(Request $request)
  {
    $ret = array();
    // session, referer, member, can_config, ไม่ใช่สมาชิกตัวอย่าง
    if ($request->initSession() && $request->isReferer() && $login = Login::adminAccess()) {
      if (Login::checkPermission($login, 'can_config') && Login::notDemoMode($login)) {
        if ($request->post('action')->toString() === 'delete') {
          $id = $request->post('id')->toInt();
          $model = new \Kotchasan\Model;
          $model->db()->delete($model->getTableName('emailtemplate'), array(
            array('id', $id),
            array('email_id', 0)
          ));
          // คืนค่า
          $ret['delete_id'] = $request->post('src')->toString().'_'.$id;
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