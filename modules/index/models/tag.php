<?php
/**
 * @filesource modules/index/models/tag.php
 *
 * @see http://www.kotchasan.com/
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 */

namespace Index\Tag;

/**
 * Model สำหรับลิสต์รายการ Tag.
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * query รายการ tag ทั้งหมด
     * เรียงลำดับตาม count.
     *
     * @return array
     */
    public static function all()
    {
        $model = new static();

        return $model->db()->createQuery()
            ->select()
            ->from('tags')
            ->order('count')
            ->toArray()
            ->execute();
    }

    /**
     * ลิสต์รายการ Tag สำหรับใส่ลง select.
     *
     * @return array
     */
    public static function toSelect()
    {
        $model = new static();
        $query = $model->db()->createQuery()
            ->select()
            ->from('tags')
            ->order('tag')
            ->toArray();
        $result = array();
        foreach ($query->execute() as $item) {
            $result[$item['tag']] = $item['tag'];
        }

        return $result;
    }
}
