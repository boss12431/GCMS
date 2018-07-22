<?php
/**
 * @filesource modules/index/models/updateprofile.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Updateprofile;

use Gcms\Login;
use Kotchasan\File;
use Kotchasan\Http\Request;
use Kotchasan\Language;

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
     * แก้ไขข้อมูลสมาชิก (editprofile.php).
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, สมาชิก และไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isSafe() && $login = Login::isMember()) {
            if (Login::notDemoMode($login)) {
                // รับค่าจากการ POST
                $save = array();
                foreach ($request->getParsedBody() as $key => $value) {
                    $k = str_replace('register_', '', $key);
                    switch ($k) {
                        case 'phone1':
                        case 'phone2':
                        case 'provinceID':
                        case 'zipcode':
                        case 'idcard':
                            $save[$k] = $request->post($key)->number();
                            break;
                        case 'sex':
                            $save['sex'] = $request->post('register_sex')->topic();
                            break;
                        case 'displayname':
                        case 'name':
                        case 'address1':
                        case 'address2':
                        case 'province':
                        case 'country':
                            $save[$k] = $request->post($key)->topic();
                            break;
                        case 'website':
                            $save[$k] = $request->post($key)->url();
                            break;
                        case 'birthday':
                            $save[$k] = $request->post($key)->date();
                            break;
                        case 'password':
                        case 'repassword':
                            $$k = $request->post($key)->text();
                            break;
                    }
                }
                if (isset($save['country']) && $save['country'] == 'TH') {
                    // จังหวัดจาก provinceID ถ้าเลือกประเทศไทย
                    $save['province'] = \Kotchasan\Province::get($save['provinceID']);
                }
                // ชื่อตาราง user
                $user_table = $this->getTableName('user');
                // database connection
                $db = $this->db();
                // ตรวจสอบค่าที่ส่งมา
                $user = $db->first($user_table, $request->post('register_id')->toInt());
                if (!$user) {
                    // ไม่พบสมาชิกที่แก้ไข
                    $ret['alert'] = Language::get('not a registered user');
                } else {
                    // ชื่อเล่น
                    if (isset($save['displayname'])) {
                        if (mb_strlen($save['displayname']) < 2) {
                            $ret['ret_register_displayname'] = Language::get('Name for the show on the site at least 2 characters');
                        } elseif (in_array($save['displayname'], self::$cfg->member_reserv)) {
                            $ret['ret_register_displayname'] = Language::get('Invalid name');
                        } else {
                            // ตรวจสอบ displayname ซ้ำ
                            $search = $db->first($user_table, array('displayname', $save['displayname']));
                            if ($search !== false && $user->id != $search->id) {
                                $ret['ret_register_displayname'] = Language::replace('This :name already exist', array(':name' => Language::get('Name')));
                            }
                        }
                    }
                    // ชื่อ
                    if (isset($save['name'])) {
                        if ($save['name'] == '') {
                            $ret['ret_register_name'] = 'Please fill in';
                        }
                    }
                    // โทรศัพท์
                    if (!empty($save['phone1'])) {
                        if (!preg_match('/[0-9]{9,10}/', $save['phone1'])) {
                            $ret['ret_register_phone1'] = Language::replace('Invalid :name', array(':name' => Language::get('Phone number')));
                        } else {
                            // ตรวจสอบโทรศัพท์ซ้ำ
                            $search = $db->first($user_table, array('phone1', $save['phone1']));
                            if ($search !== false && $user->id != $search->id) {
                                $ret['ret_register_phone1'] = Language::replace('This :name already exist', array(':name' => Language::get('Phone number')));
                            }
                        }
                    }
                    // แก้ไขรหัสผ่าน
                    if ($user->fb == 0 && (!empty($password) || !empty($repassword))) {
                        if (mb_strlen($password) < 4) {
                            // รหัสผ่านต้องไม่น้อยกว่า 4 ตัวอักษร
                            $ret['ret_register_password'] = 'this';
                        } elseif ($repassword != $password) {
                            // ถ้าต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านสองช่องให้ตรงกัน
                            $ret['ret_register_repassword'] = 'this';
                        } else {
                            // password ใหม่ถูกต้อง
                            $save['salt'] = uniqid();
                            $save['password'] = md5($password.$save['salt']);
                        }
                    }
                    if (empty($ret)) {
                        // อัปโหลดไฟล์
                        foreach ($request->getUploadedFiles() as $item => $file) {
                            /* @var $file \Kotchasan\Http\UploadedFile */
                            if ($file->hasUploadFile()) {
                                if (!File::makeDirectory(ROOT_PATH.self::$cfg->usericon_folder)) {
                                    // ไดเรคทอรี่ไม่สามารถสร้างได้
                                    $ret['ret_'.$item] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), self::$cfg->usericon_folder);
                                } else {
                                    try {
                                        // อัปโหลด user icon
                                        $save['icon'] = $user->id.'.jpg';
                                        $file->cropImage(self::$cfg->user_icon_typies, ROOT_PATH.self::$cfg->usericon_folder.$save['icon'], self::$cfg->user_icon_w, self::$cfg->user_icon_h);
                                    } catch (\Exception $exc) {
                                        // ไม่สามารถอัปโหลดได้
                                        $ret['ret_'.$item] = Language::get($exc->getMessage());
                                    }
                                }
                            } elseif ($file->hasError()) {
                                // ข้อผิดพลาดการอัปโหลด
                                $ret['ret_'.$item] = Language::get($file->getErrorMessage());
                            }
                        }
                    }
                    if (!empty($save) && empty($ret)) {
                        // save
                        $db->update($user_table, $user->id, $save);
                        // เปลี่ยน password ที่ login ใหม่
                        if (!empty($save['password'])) {
                            $_SESSION['login']['password'] = $password;
                        }
                        // คืนค่า
                        $ret['alert'] = Language::get('Saved successfully');
                        $ret['location'] = 'reload';
                        // เคลียร์
                        $request->removeToken();
                    }
                }
            } else {
                // โหมดตัวอย่าง
                $ret['alert'] = Language::get('Unable to complete the transaction');
            }
        }
        // คืนค่าเป็น JSON
        if (!empty($ret)) {
            echo json_encode($ret);
        }
    }
}
