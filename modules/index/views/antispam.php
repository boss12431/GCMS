<?php
/**
 * @filesource modules/index/views/antispam.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Antispam;

use Kotchasan\Antispam;
use Kotchasan\Http\Request;

/**
 * Antispam Image.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    public function index(Request $request)
    {
        $request->initSession();
        // Antispam Image
        Antispam::createImage($request->get('id')->toString());
    }
}
