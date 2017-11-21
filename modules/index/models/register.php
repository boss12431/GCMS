<?php
/**
 * @filesource modules/index/models/register.php
 * @link http://www.kotchasan.com/
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Register;

use \Kotchasan\Language;
use \Kotchasan\Http\Request;
use \Kotchasan\Validator;
use \Kotchasan\Text;
use \Gcms\Email;

/**
 * บันทึกข้อมูลสมาชิก
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{

  /**
   * บันทึก
   */
  public function submit(Request $request)
  {
    $ret = array();
    // session, token
    if ($request->initSession() && $request->isSafe()) {
      // รับค่าจากการ POST
      $save = array();
      foreach ($request->getParsedBody() as $key => $value) {
        $k = str_replace('register_', '', $key);
        switch ($k) {
          case 'email':
            $save['email'] = $request->post($key)->username();
            break;
          case 'phone1':
          case 'idcard':
            $save[$k] = $request->post($key)->number();
            break;
          case 'password':
          case 'repassword':
          case 'accept':
          case 'next':
            $$k = $request->post($key)->toString();
            break;
        }
      }
      if ($accept === '1') {
        // ชื่อตาราง user
        $user_table = $this->getTableName('user');
        // database connection
        $db = $this->db();
        // อีเมล์
        if (empty($save['email'])) {
          $ret['ret_register_email'] = 'Please fill in';
        } elseif (!Validator::email($save['email'])) {
          $ret['ret_register_email'] = Language::replace('Invalid :name', array(':name' => Language::get('Email')));
        } else {
          // ตรวจสอบอีเมล์ซ้ำ
          $search = $db->first($user_table, array('email', $save['email']));
          if ($search !== false) {
            $ret['ret_register_email'] = Language::replace('This :name already exist', array(':name' => Language::get('Email')));
          }
        }
        // password
        if (mb_strlen($password) < 4) {
          // รหัสผ่านต้องไม่น้อยกว่า 4 ตัวอักษร
          $ret['ret_register_password'] = Language::get('Passwords must be at least four characters');
        } elseif ($repassword != $password) {
          // กรอกรหัสผ่านสองช่องให้ตรงกัน
          $ret['ret_register_repassword'] = Language::get('Enter your password to match the two inputs');
        } else {
          $save['salt'] = uniqid();
          $save['password'] = md5($password.$save['salt']);
        }
        // phone1
        if (!empty($save['phone1'])) {
          if (!preg_match('/[0-9]{9,10}/', $save['phone1'])) {
            $ret['ret_register_phone1'] = Language::replace('Invalid :name', array(':name' => Language::get('Phone number')));
          } else {
            // ตรวจสอบโทรศัพท์ซ้ำ
            $search = $db->first($user_table, array('phone1', $save['phone1']));
            if ($search !== false) {
              $ret['ret_register_phone1'] = Language::replace('This :name already exist', array(':name' => Language::get('Phone number')));
            }
          }
        } elseif (self::$cfg->member_phone == 2) {
          $ret['ret_register_phone1'] = 'this';
        }
        // idcard
        if (!empty($save['idcard'])) {
          if (!Validator::idCard($save['idcard'])) {
            $ret['ret_register_idcard'] = Language::replace('Invalid :name', array(':name' => Language::get('Identification number')));
          } else {
            // ตรวจสอบ idcard ซ้ำ
            $search = $db->first($user_table, array('idcard', $save['idcard']));
            if ($search !== false) {
              $ret['ret_register_idcard'] = Language::replace('This :name already exist', array(':name' => Language::get('Identification number')));
            }
          }
        } elseif (self::$cfg->member_idcard == 2) {
          $ret['ret_register_idcard'] = 'this';
        }
        if (empty($ret)) {
          $save['create_date'] = time();
          $save['active'] = 0;
          $save['status'] = self::$cfg->new_register_status;
          list($displayname, $domain) = explode('@', $save['email']);
          $save['displayname'] = $displayname;
          $save['name'] = ucwords($displayname);
          $a = 1;
          while (true) {
            if (false === $db->first($user_table, array('displayname', $save['displayname']))) {
              break;
            } else {
              $a++;
              $save['displayname'] = $displayname.$a;
            }
          }
          // รหัสยืนยัน
          $save['activatecode'] = empty(self::$cfg->user_activate) ? '' : Text::rndname(32);
          // บันทึกลงฐานข้อมูล
          $save['id'] = $db->insert($user_table, $save);
          // ส่งอีเมล์
          $replace = array(
            '/%EMAIL%/' => $save['email'],
            '/%PASSWORD%/' => $password,
            '/%ID%/' => $save['activatecode']
          );
          Email::send(empty(self::$cfg->user_activate) ? 2 : 1, 'member', $replace, $save['email']);
          if (empty(self::$cfg->user_activate)) {
            // login
            $save['password'] = $password;
            $_SESSION['login'] = $save;
            // แสดงข้อความตอบรับการสมัครสมาชิก
            $ret['alert'] = Language::replace('Registration information sent to :email complete. We will take you to edit your profile', array(':email' => $save['email']));
            // ถ้าไม่มีการกำหนดหน้าถัดไปมา ไปแก้ไขข้อมูลส่วนตัว
            $ret['location'] = isset($next) ? $next : WEB_URL.'index.php?module=editprofile';
          } else {
            // แสดงข้อความตอบรับการสมัครสมาชิก
            $ret['alert'] = Language::replace('Register successfully, We have sent complete registration information to :email', array(':email' => $save['email']));
            // ถ้าไม่มีการกำหนดหน้าถัดไปมา กลับไปหน้าหลักเว็บไซต์
            $ret['location'] = isset($next) ? $next : WEB_URL.'index.php';
          }
          // เคลียร์
          $request->removeToken();
        }
      }
    }
    // คืนค่าเป็น JSON
    if (!empty($ret)) {
      echo json_encode($ret);
    }
  }
}