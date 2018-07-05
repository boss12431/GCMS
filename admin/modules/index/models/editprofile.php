<?php
/**
 * @filesource modules/index/models/editprofile.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Editprofile;

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
     * อ่านข้อมูลสมาชิกที่ $user_id.
     *
     * @param int $user_id
     *
     * @return array|null คืนค่า array ของข้อมูล ไม่พบคืนค่า null
     */
    public static function get($user_id)
    {
        if (!empty($user_id)) {
            // query ข้อมูลที่เลือก
            $model = new \Kotchasan\Model();
            $user = $model->db()->createQuery()
                ->from('user')
                ->where(array('id', $user_id))
                ->toArray()
                ->first();
            if ($user) {
                // permission
                $user['permission'] = empty($user['permission']) ? array() : explode(',', trim($user['permission'], " \t\n\r\0\x0B,"));

                return $user;
            }
        }

        return null;
    }

    /**
     * แก้ไขข้อมูลสมาชิก (editprofile.php).
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // session, token, สมาชิก และไม่ใช่สมาชิกตัวอย่าง
        if ($request->initSession() && $request->isSafe() && $login = Login::adminAccess()) {
            if (Login::notDemoMode($login)) {
                // รับค่าจากการ POST
                $save = array(
                    'email' => $request->post('register_email')->url(),
                    'displayname' => $request->post('register_displayname')->topic(),
                    'sex' => $request->post('register_sex')->filter('a-z'),
                    'website' => $request->post('register_website')->url(),
                    'name' => $request->post('register_name')->topic(),
                    'idcard' => $request->post('register_idcard')->number(),
                    'company' => $request->post('register_company')->topic(),
                    'phone1' => $request->post('register_phone1')->number(),
                    'phone2' => $request->post('register_phone2')->number(),
                    'address1' => $request->post('register_address1')->topic(),
                    'address2' => $request->post('register_address2')->topic(),
                    'provinceID' => $request->post('register_provinceID')->number(),
                    'province' => $request->post('register_province')->topic(),
                    'zipcode' => $request->post('register_zipcode')->number(),
                    'country' => $request->post('register_country')->topic(),
                    'status' => $request->post('register_status')->toInt(),
                    'birthday' => $request->post('register_birthday')->date(),
                );
                $permission = $request->post('register_permission', array())->topic();
                // ชื่อตาราง user
                $user_table = $this->getTableName('user');
                // database connection
                $db = $this->db();
                // ตรวจสอบค่าที่ส่งมา
                $user = $db->first($user_table, $request->post('register_id')->toInt());
                // ตัวเอง, แอดมินแก้ไขได้ทุกคน ยกเว้น ID 1
                if ($user && ($user->id == $login['id'] || ($user->id > 1 && $login['status'] == 1))) {
                    // แอดมิน สามารถแก้ไขได้ทุกอย่าง
                    $isAdmin = $login['status'] == 1;
                    // ไม่ใช่แอดมิน ใช้อีเมลเดิมจากฐานข้อมูล
                    if (!$isAdmin && $user->id > 0) {
                        $save['email'] = $user->email;
                    }
                    // ตรวจสอบค่าที่ส่งมา
                    $requirePassword = false;
                    // อีเมล
                    if (empty($save['email'])) {
                        $ret['ret_register_email'] = 'this';
                    } else {
                        // ตรวจสอบอีเมลซ้ำ
                        $search = $db->first($user_table, array('email', $save['email']));
                        if ($search !== false && $user->id != $search->id) {
                            $ret['ret_register_email'] = Language::replace('This :name already exist', array(':name' => Language::get('Email')));
                        } else {
                            $requirePassword = $user->email !== $save['email'];
                        }
                    }
                    // ชื่อเรียก
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
                    // ชื่อ
                    if ($save['name'] == '') {
                        $ret['ret_register_name'] = 'Please fill in';
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
                    $password = $request->post('register_password')->password();
                    $repassword = $request->post('register_repassword')->password();
                    if (!empty($password) || !empty($repassword)) {
                        if (mb_strlen($password) < 4) {
                            // รหัสผ่านต้องไม่น้อยกว่า 4 ตัวอักษร
                            $ret['ret_register_password'] = 'this';
                        } elseif ($repassword != $password) {
                            // ถ้าต้องการเปลี่ยนรหัสผ่าน กรุณากรอกรหัสผ่านสองช่องให้ตรงกัน
                            $ret['ret_register_repassword'] = 'this';
                        } else {
                            // password ใหม่ถูกต้อง
                            $save['salt'] = uniqid();
                            $save['password'] = md5($password . $save['salt']);
                            $requirePassword = false;
                        }
                    }
                    // มีการเปลี่ยน email ต้องการรหัสผ่าน
                    if (empty($ret) && $requirePassword) {
                        $ret['ret_register_password'] = 'this';
                    }
                    if (empty($ret)) {
                        // อัปโหลดไฟล์
                        foreach ($request->getUploadedFiles() as $item => $file) {
                            /* @var $file \Kotchasan\Http\UploadedFile */
                            if ($file->hasUploadFile()) {
                                if (!File::makeDirectory(ROOT_PATH . self::$cfg->usericon_folder)) {
                                    // ไดเรคทอรี่ไม่สามารถสร้างได้
                                    $ret['ret_' . $item] = sprintf(Language::get('Directory %s cannot be created or is read-only.'), self::$cfg->usericon_folder);
                                } else {
                                    try {
                                        // อัปโหลด user icon
                                        $save['icon'] = $user->id . '.jpg';
                                        $file->cropImage(self::$cfg->user_icon_typies, ROOT_PATH . self::$cfg->usericon_folder . $save['icon'], self::$cfg->user_icon_w, self::$cfg->user_icon_h);
                                    } catch (\Exception $exc) {
                                        // ไม่สามารถอัปโหลดได้
                                        $ret['ret_' . $item] = Language::get($exc->getMessage());
                                    }
                                }
                            } elseif ($file->hasError()) {
                                // ข้อผิดพลาดการอัปโหลด
                                $ret['ret_' . $item] = Language::get($file->getErrorMessage());
                            }
                        }
                    }
                    if (empty($ret)) {
                        if ($save['country'] == 'TH') {
                            // จังหวัดจาก provinceID ถ้าเลือกประเทศไทย
                            $save['province'] = \Kotchasan\Province::get($save['provinceID']);
                        }
                        if ($login['status'] == 1 && $user->id != 1 && $login['id'] != $user->id) {
                            // แอดมินแต่ไม่ใช่ตัวเองและแก้ไขสมาชิกสถานะอื่น
                            $save['permission'] = empty($permission) ? '' : ',' . implode(',', $permission) . ',';
                        } else {
                            // ตัวเอง ห้ามแก้ไข
                            unset($save['status']);
                            unset($save['point']);
                        }
                        if (!empty($user->fb)) {
                            // social ห้ามแก้ไข
                            unset($save['email']);
                            unset($save['password']);
                        }
                        // บันทึก
                        $db->update($user_table, $user->id, $save);
                        if ($login['id'] == $user->id) {
                            // ตัวเอง
                            if (isset($save['password'])) {
                                if (isset($save['email'])) {
                                    $_SESSION['login']['email'] = $save['email'];
                                }
                                $_SESSION['login']['password'] = $password;
                            }
                            // reload หน้าเว็บ
                            $ret['location'] = 'reload';
                        } else {
                            // กลับไปหน้าก่อนหน้า
                            $ret['location'] = $request->getUri()->postBack('index.php', array('id' => null));
                        }
                        // คืนค่า
                        $ret['alert'] = Language::get('Saved successfully');
                        // เคลียร์
                        $request->removeToken();
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
