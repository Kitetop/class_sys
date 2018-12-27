<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-26
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;

/**
 * Class UploadGrade
 * @package App\Action\User
 * 给指定的稿件评分
 */
class UploadGrade extends AbstractAction
{
    private $postRules = [
        'id' => [
            'rules' => ['required'],
            'desc' => '当前登陆的用户id',
            'message' => '用户id不能为空',
        ],
        'article_id' => [
            'rules' => ['required'],
            'desc' => '进行评分的文章的文章id',
            'message' => '文章id不能为空',
        ],
        'original_grade' => [
            'desc' => '原创性评分',
            'rules' => ['required'],
        ],
        'format_grade' => [
            'rules' => ['required'],
            'desc' => '格式评分',
        ],
        'content_grade' => [
            'rules' => ['required'],
            'desc' => '内容评分',
        ],
        'reference_grade' => [
            'rules' => ['required'],
            'desc' => '参考评分',
        ],
        'whole_grade' => [
            'rules' => ['required'],
            'desc' => '总体评分',
        ]
    ];

    protected function doPost()
    {
        $this->validate($this->postRules);
        $service = $this->Service('User\UploadGrade');
        $service->id = $this->params['id'];
        $service->article_id = $this->params['article_id'];
        $service->original_grade = $this->params['original_grade'];
        $service->format_grade = $this->params['format_grade'];
        $service->content_grade = $this->params['content_grade'];
        $service->reference_grade = $this->params['reference_grade'];
        $service->whole_grade = $this->params['whole_grade'];
        $service->run();
        $this->response('message', '评分成功，感谢你的配合');
        $this->code(200);
    }
}