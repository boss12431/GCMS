<?php
/**
 * @filesource modules/index/views/report.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Report;

use Kotchasan\DataTable;
use Kotchasan\Date;
use Kotchasan\Http\Request;

/**
 * ฟอร์ม forgot.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\Adminview
{
    private $date;

    /**
     * แสดงข้อมูลประวัติการเยียมชม
     *
     * @param Request $request
     * @param string  $ip
     * @param string  $date
     *
     * @return string
     */
    public function render(Request $request, $ip, $date)
    {
        $logs = \Index\Report\Model::logPerHour($date);
        $thead = array();
        $tbody = array();
        for ($i = 0; $i < 24; ++$i) {
            $thead[] = '<th>'.($i + 1).'</th>';
            $tbody[] = '<td>'.(isset($logs[$i]) ? $logs[$i] : 0).'</td>';
        }
        $content = '<div id=report_graph class="ggraphs">';
        $content .= '<canvas></canvas>';
        $content .= '<table class="hidden">';
        $content .= '<thead><tr><th>{LNG_hour}</th>'.implode('', $thead).'</tr></thead>';
        $content .= '<tbody>';
        $content .= '<tr><th scope=row>{LNG_People visit the site}</th>'.implode('', $tbody).'</tr>';
        $content .= '</tbody>';
        $content .= '</table>';
        $content .= '</div>';
        $content .= '<script>';
        $content .= 'new GGraphs("report_graph", {type:"line"});';
        $content .= '</script>';
        // วันที่ ที่กำลังแสดงอยู่
        $this->date = $date;
        // URL สำหรับส่งให้ตาราง
        $uri = $request->createUriWithGlobals(WEB_URL.'admin/index.php');
        // ตาราง
        $table = new DataTable(array(
            /* Uri */
            'uri' => $uri,
            /* Model */
            'model' => \Index\Report\Model::get($ip, $date),
            /* รายการต่อหน้า */
            'perPage' => $request->cookie('counter_perPage', 30)->toInt(),
            /* เรียงลำดับ */
            'sort' => $request->cookie('counter_sort', 'time desc')->toString(),
            /* คอลัมน์ที่สามารถค้นหาได้ */
            'searchColumns' => array('referer'),
            /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
            'onRow' => array($this, 'onRow'),
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => array(
                'time' => array(
                    'text' => '{LNG_Time}',
                    'sort' => 'time',
                ),
                'ip' => array(
                    'text' => '{LNG_IP}',
                    'sort' => 'ip',
                ),
                'count' => array(
                    'text' => '{LNG_Count}',
                    'class' => 'center',
                    'sort' => 'count',
                ),
                'referer' => array(
                    'text' => '{LNG_Referer}',
                    'sort' => 'referer',
                ),
                'user_agent' => array(
                    'text' => '{LNG_User Agent}',
                    'class' => 'tablet',
                    'sort' => 'user_agent',
                ),
            ),
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => array(
                'count' => array(
                    'class' => 'center',
                ),
                'referer' => array(
                    'width' => 'width:30%',
                ),
                'user_agent' => array(
                    'class' => 'tablet',
                ),
            ),
        ));
        // save cookie
        setcookie('counter_perPage', $table->perPage, time() + 2592000, '/', null, null, true);
        setcookie('counter_sort', $table->sort, time() + 2592000, '/', null, null, true);
        // คืนค่า HTML
        return $content.$table->render();
    }

    /**
     * จัดรูปแบบการแสดงผลในแต่ละแถว.
     *
     * @param array  $item ข้อมูลแถว
     * @param int    $o    ID ของข้อมูล
     * @param object $prop กำหนด properties ของ TR
     *
     * @return array คืนค่า $item กลับไป
     */
    public function onRow($item, $o, $prop)
    {
        $item['time'] = Date::format($item['time'], 'H:i:s');
        if (preg_match_all('%(?P<browser>Firefox|Safari|MSIE|AppleWebKit|bingbot|MJ12bot|Baiduspider|Googlebot|DotBot|Twitterbot|LivelapBot|facebookexternalhit|StatusNet|PaperLiBot|SurdotlyBot|Trident|archive\.org_bot|Yahoo\!\sSlurp|Go[a-z\-]+)([\/\s](?P<version>[^;\s]+))?%ix', $item['user_agent'], $result, PREG_PATTERN_ORDER)) {
            $item['user_agent'] = '<span title="'.$item['user_agent'].'">'.$result['browser'][0].(empty($result['version'][0]) ? '' : '/'.$result['version'][0]).'</span>';
        } elseif ($item['user_agent'] != '') {
            $item['user_agent'] = '<span title="'.$item['user_agent'].'">unknown</span>';
        }
        if ($item['referer'] != '') {
            if (preg_match('/^(https?:\/\/[a-z]+\.google(.*)?.*)\/.*[\&\?](url|q)=([^&]+)($|\&.*)/iu', $item['referer'], $match)) {
                // จาก google
                $title = $match[4];
            } else {
                $title = $item['referer'];
            }
            $item['referer'] = '<a href="'.$item['referer'].'" target=_blank class="cuttext block" style="max-width:30em">'.$title.'</a>';
        }
        $item['ip'] = '<a href="index.php?module=report&amp;ip='.$item['ip'].'&amp;date='.$this->date.'">'.$item['ip'].'</a>';

        return $item;
    }
}
