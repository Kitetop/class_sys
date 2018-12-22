<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018/12/18
 */

namespace Kite\Commons;


class Page
{
    public static function simple($meta, $url, $query)
    {
        $data['prev'] = $data['next'] = '';

        if ($meta['page'] > 1) {
            $query['page'] = $meta['page'] - 1;
            $data['prev'] = $url . http_build_query($query);
        }
        if ($meta['page'] < $meta['pages']) {
            $query['page'] = $meta['page'] + 1;
            $data['next'] = $url . http_build_query($query);
        }

        return [$data['prev'], $data['next']];
    }

    /**
     * @param array $result
     * @param $total
     * @param $page
     * @param $limit
     * 对数据进行封装
     */
    public static function assemble(array &$result, $total, $page, $limit)
    {
        $result['total'] = $total;
        $result['meta'] = [
            'page' => $page,
            'total' => $total,
            'pages' => ceil($total / $limit)
        ];
    }
}
