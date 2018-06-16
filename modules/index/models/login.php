<?php
/**
 * @filesource modules/index/models/login.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Login;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Template;

/**
 * Controller หลัก สำหรับแสดง frontend ของ GCMS.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\KBase
{
    /**
     * ฟังก์ชั่นตรวจสอบการ Login.
     *
     * @param Request $request
     */
    public function chklogin(Request $request)
    {
        if ($request->initSession() && $request->isSafe()) {
            // template ที่กำลังใช้งานอยู่
            if (!empty($_SESSION['skin']) && is_file(APP_PATH.'skin/'.$_SESSION['skin'].'/style.css')) {
                self::$cfg->skin = $_SESSION['skin'];
            }
            Template::init('skin/'.self::$cfg->skin);
            // ตรวจสอบการ login
            Login::create();
            // ตรวจสอบสมาชิก
            $login = Login::isMember();
            if ($login) {
                $ret = array(
                    'alert' => Language::replace('Welcome %s, login complete', array('%s' => empty($login['name']) ? $login['email'] : $login['name'])),
                    'content' => rawurlencode(\Index\Login\Controller::init($login)),
                    'action' => $request->post('login_action', self::$cfg->login_action)->toString(),
                );
                // เคลียร์
                $request->removeToken();
            } else {
                $ret = array(
                    'alert' => Login::$login_message,
                    'input' => Login::$login_input,
                );
            }
            // คืนค่า JSON
            echo json_encode($ret);
        }
    }
}
