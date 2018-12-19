<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release: v1.0
 * Date: 2018/12/18
 */

namespace App\Action\User;


use Kite\Action\AbstractAction;

class UploadArticle extends AbstractAction
{
    private $postRules = [
        'up_id' => [
            'desc' => '用户的id',
            'rules' => ['required'],
            'message' => '用户id不能为空'
        ],
        'active_id' => [
            'desc' => '活动的id',
            'rules' => ['required'],
            'message' => '活动id不能为空'
        ],
        'article_message' => [
            'desc' => '文章信息',
            'rules' => ['required'],
            'message' => '文章提示信息不能为空'
        ],
        'first_author' => ['desc' => '第一作者'],
        'second_author' => ['desc' => '第二作者'],
        'third_author' => ['desc' => '第三作者'],
        'wish' => ['desc' => '期望审稿人'],
    ];

    private $fileRules = [
        'article' => [
            'rules' => ['required', 'mime:application/pdf,application/zip'],
            'desc' => '论文文件',
            'message' => '请上传PDF或者zip文件'
        ]
    ];

    protected function doPost()
    {
        $this->validateUploadFile($this->fileRules);
        //$this->validate($this->postRules);
//        if (!isset($this->params['first_author'])
//            && !isset($this->params['second_author'])
//            && !isset($this->params['third_author'])) {
//            throw new \Exception('参稿作者不能全为空', 400);
//        }
        $service = $this->Service('User\UploadArticle');
        $service->article = $this->validatedFiles['article'];
        $service->up_id = $this->params['up_id'];
        $service->active_id = $this->params['active_id'];
        $service->article_message = $this->params['article_message'];
        $service->first_author = $this->params['first_author'];
        $service->second_author = $this->params['second_author'];
        $service->third_author = $this->params['third_author'];
        $service->wish = $this->params['wish'];
        $result = $service->run();
        $this->response($result);
        $this->code(201);
    }
}