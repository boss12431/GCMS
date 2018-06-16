<?php
/**
 * @filesource modules/index/views/dologin.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Dologin;

use Gcms\Gcms;
use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;
use Kotchasan\Template;

/**
 * module=dologin.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * หน้า login.
     *
     * @param Request $request
     *
     * @return object
     */
    public function render(Request $request)
    {
        $sign_in = Language::get('Sign in');
        $index = (object) array(
            'canonical' => WEB_URL.'index.php?module=dologin',
            'topic' => $sign_in,
            'description' => self::$cfg->web_description,
            'menu' => 'dologin',
        );
        $template = Template::create('member', 'member', 'loginfrm');
        $template->add(array(
            '/{TOKEN}/' => $request->createToken(),
            '/{EMAIL}/' => isset(Login::$login_params['username']) ? Login::$login_params['username'] : '',
            '/{PASSWORD}/' => isset(Login::$login_params['password']) ? Login::$login_params['password'] : '',
            '/{REMEMBER}/' => self::$request->cookie('login_remember')->toInt() == 1 ? 'checked' : '',
            '/{FACEBOOK}/' => empty(self::$cfg->facebook_appId) ? 'hidden' : 'facebook',
            '/{TOPIC}/' => $index->topic,
            '/{SUBTITLE}/' => $index->description,
            '/{PLACEHOLDER}/' => Gcms::getLoginPlaceholder(),
        ));
        $index->detail = $template->render();
        $index->keywords = $index->topic;
        if (isset(Gcms::$view)) {
            Gcms::$view->addBreadcrumb($index->canonical, $sign_in);
        }

        return $index;
    }
}
