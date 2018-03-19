<?php
/**
 * @filesource Gcms/Login.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Gcms;

use \Kotchasan\Language;
use \Kotchasan\Http\Request;

/**
 * คลาสสำหรับตรวจสอบการ Login
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Login extends \Kotchasan\Login implements \Kotchasan\LoginInterface
{

  /**
   * ฟังก์ชั่นตรวจสอบสมาชิกกับฐานข้อมูล
   *
   * @param array $params
   * @return array|string คืนค่าข้อมูลสมาชิก (array) ไม่พบคืนค่าข้อความผิดพลาด (string)
   */
  public static function checkMember($params)
  {
    // query Where
    $where = array();
    foreach (self::$cfg->login_fields as $field) {
      $where[] = array("U.{$field}", $params['username']);
    }
    // model
    $model = new \Kotchasan\Model;
    $query = $model->db()->createQuery()
      ->select()
      ->from('user U')
      ->where($where, 'OR')
      ->order('U.status DESC')
      ->toArray();
    $login_result = null;
    foreach ($query->execute() as $item) {
      if ($item['password'] == md5($params['password'].$item['salt'])) {
        $item['permission'] = empty($item['permission']) ? array() : trim(explode(',', $item['permission']), " \t\n\r\0\x0B,");
        $login_result = $item;
        break;
      }
    }
    if ($login_result === null) {
      // user หรือ password ไม่ถูกต้อง
      self::$login_input = isset($item) ? 'password' : 'username';
      return isset($item) ? Language::replace('Incorrect :name', array(':name' => Language::get('Password'))) : Language::get('not a registered user');
    } elseif (!empty($login_result['activatecode'])) {
      // ยังไม่ได้ activate
      self::$login_input = 'username';
      return Language::get('No confirmation email, please check your e-mail');
    } elseif (!empty($login_result['ban'])) {
      // ติดแบน
      self::$login_input = 'username';
      return Language::get('Members were suspended');
    } else {
      return $login_result;
    }
  }

  /**
   * ฟังก์ชั่นตรวจสอบการ login และบันทึกการเข้าระบบ
   *
   * @param array $params ข้อมูลการ login ที่ส่งมา $params = array('username' => '', 'password' => '');
   * @return string|array เข้าระบบสำเร็จคืนค่าแอเรย์ข้อมูลสมาชิก, ไม่สำเร็จ คืนค่าข้อความผิดพลาด
   */
  public function checkLogin($params)
  {
    // ตรวจสอบสมาชิกกับฐานข้อมูล
    $login_result = self::checkMember($params);
    if (is_string($login_result)) {
      return $login_result;
    } else {
      // model
      $model = new \Kotchasan\Model;
      // ip ที่ login
      $ip = self::$request->getClientIp();
      // ตรวจสอบการ login มากกว่า 1 ip
      if (self::$cfg->member_only_ip && !empty($ip)) {
        $online = $model->db()->createQuery()
          ->from('useronline')
          ->where(array(
            array('member_id', (int)$login_result['id']),
            array('ip', 'NOT IN', array('', $ip))
          ))
          ->order('time DESC')
          ->toArray()
          ->first('time');
        if ($online && time() - (int)$online['time'] < 60) {
          // login ต่าง ip กัน
          return Language::get('Members of this system already');
        }
      }
      // current session
      $session_id = session_id();
      // อัปเดทการเยี่ยมชม
      if ($session_id != $login_result['session_id']) {
        $login_result['visited'] ++;
        $model->db()->createQuery()
          ->update('user')
          ->set(array(
            'session_id' => $session_id,
            'visited' => $login_result['visited'],
            'lastvisited' => time(),
            'ip' => $ip
          ))
          ->where((int)$login_result['id'])
          ->execute();
      }
    }
    return $login_result;
  }

  /**
   * ตรวจสอบความสามารถในการตั้งค่า
   * แอดมินสูงสุด (status=1) ทำได้ทุกอย่าง
   *
   * @param array $login
   * @param array|string $permission
   * array|null คืนค่าข้อมูลสมาชิก (แอเรย์) ถ้าไม่สามารถทำรายการได้คืนค่า null
   */
  public static function checkPermission($login, $permission)
  {
    if (!empty($login)) {
      if ($login['status'] == 1) {
        // แอดมิน
        return $login;
      } elseif (!empty($permission)) {
        if (is_array($permission)) {
          foreach ($permission as $item) {
            if (in_array($item, $login['permission'])) {
              // มีสิทธิ์
              return $login;
            }
          }
        } elseif (in_array($permission, $login['permission'])) {
          // มีสิทธิ์
          return $login;
        }
      }
    }
    // ไม่มีสิทธิ
    return null;
  }

  /**
   * ตรวจสอบความสามารถในการเข้าระบบแอดมิน
   *
   * @return array|null คืนค่าข้อมูลสมาชิก (แอเรย์) ถ้าสามารถเข้าระบบแอดมินได้ ไม่ใช่คืนค่า null
   */
  public static function adminAccess()
  {
    $login = self::isMember();
    return isset($login['active']) && $login['active'] == 1 ? $login : null;
  }

  /**
   * ฟังก์ชั่นตรวจสอบว่า เป็นสมาชิกตัวอย่างหรือไม่
   *
   * @param array|null $login
   * @return array|null คืนค่าข้อมูลสมาชิก (แอเรย์) ถ้าไม่ใช่สมาชิกตัวอย่าง, null ถ้าเป็นสมาชิกตัวอย่างและเปิดโหมดตัวอย่างไว้
   */
  public static function notDemoMode($login)
  {
    return $login && !empty($login['fb']) && self::$cfg->demo_mode ? null : $login;
  }

  /**
   * ฟังก์ชั่นส่งอีเมล์ลืมรหัสผ่าน
   */
  public function forgot(Request $request)
  {
    // ค่าที่ส่งมา
    $username = $request->post('login_username')->url();
    if (empty($username)) {
      if ($request->post('action')->toString() === 'forgot') {
        self::$login_message = Language::get('Please fill in');
      }
    } else {
      self::$login_params['username'] = $username;
      // ชื่อฟิลด์สำหรับตรวจสอบอีเมล์ ใช้ฟิลด์แรกจาก config
      $field = reset(self::$cfg->login_fields);
      // Model
      $model = new \Kotchasan\Model;
      // ตาราง user
      $table = $model->getTableName('user');
      // ค้นหาอีเมล์
      $search = $model->db()->first($table, array(array($field, $username), array('fb', '0')));
      if ($search === false) {
        self::$login_message = Language::get('not a registered user');
      } else {
        // สุ่มรหัสผ่านใหม่
        $password = \Kotchasan\Text::rndname(6);
        // ข้อมูลอีเมล์
        $replace = array(
          '/%PASSWORD%/' => $password,
          '/%EMAIL%/' => $search->$field
        );
        // send mail
        $err = \Gcms\Email::send(3, 'member', $replace, $search->$field);
        if (!$err->error()) {
          // อัปเดทรหัสผ่านใหม่
          $save = array(
            'salt' => uniqid()
          );
          $save['password'] = md5($password.$save['salt']);
          $model->db()->update($table, (int)$search->id, $save);
          // คืนค่า
          self::$login_message = Language::get('Your message was sent successfully');
          self::$request = $request->withQueryParams(array('action' => 'login'));
        } else {
          self::$login_message = $err->getErrorMessage();
        }
      }
    }
  }
}