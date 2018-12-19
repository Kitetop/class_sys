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
}
