<?php
/**
 * @filesource modules/index/models/upgrade1320.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Upgrade1320;

/**
 * อัปเกรด.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Index\Upgrade\Model
{
    /**
     * อัปเกรดเป็นเวอร์ชั่น 13.2.0.
     *
     * @return string
     */
    public static function upgrade($db)
    {
        $content = array();
        // logs table
        $table = $_SESSION['prefix'].'_logs';
        $db->query("DROP TABLE IF EXISTS `$table`");
        $db->query("CREATE TABLE `$table` ( `time` datetime NOT NULL, `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `session_id` varchar(32) COLLATE utf8_unicode_ci NOT NULL, `referer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL, `user_agent` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
        $content[] = '<li class="correct">Created table <b>'.$table.'</b> complete...</li>';
        $content[] = '<li class="correct">Upgrade to Version <b>13.2.0</b> complete.</li>';

        return (object) array(
            'content' => implode('', $content),
            'version' => '13.2.0',
        );
    }
}
