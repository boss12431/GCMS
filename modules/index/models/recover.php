<?php
/**
 * @filesource modules/index/models/recover.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Recover;

use Gcms\Email;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * ขอรหัสผ่านใหม่.
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
        // referer, session
        if ($request->initSession() && $request->isReferer()) {
            $ret = array();
            // ค่าที่ส่งมา
            $email = $request->post('forgot_email')->url();
            if ($email === '') {
                $ret['ret_forgot_email'] = 'Please fill in';
            } else {
                $search = $this->db()->createQuery()
                    ->from('user')
                    ->where(array(array('email', $email), array('social', 0)))
                    ->first('id', 'email', 'salt');
                if ($search === false) {
                    $ret['ret_forgot_email'] = Language::get('not a registered user');
                }
            }
            if (empty($ret)) {
                // รหัสผ่านใหม่
                $password = substr(uniqid(), 0, 6);
                // ข้อมูลอีเมล
                $replace = array(
                    '/%PASSWORD%/' => $password,
                    '/%EMAIL%/' => $search->email,
                );
                // send mail
                $err = Email::send(3, 'member', $replace, $search->email);
                if (!$err->error()) {
                    // อัปเดตรหัสผ่านใหม่
                    $save = array('password' => sha1($password.$search->salt));
                    $this->db()->createQuery()->update('user')->set($save)->where($search->id)->execute();
                    // คืนค่า
                    $ret['alert'] = Language::get('Your message was sent successfully');
                    $location = $request->post('modal')->url();
                    $ret['location'] = $location === 'true' ? 'close' : $location;
                } else {
                    $ret['ret_forgot_email'] = $err->getErrorMessage();
                }
            }
            // คืนค่าเป็น JSON
            echo json_encode($ret);
        }
    }
}
