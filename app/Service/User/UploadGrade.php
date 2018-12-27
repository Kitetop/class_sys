<?php
/**
 * @author Kitetop <1363215999@qq.com>
 * @version Release:
 * Date: 2018-12-26
 */

namespace App\Service\User;


use App\Model\Sys_check;
use App\Model\Sys_grade;
use Kite\Service\AbstractService;
use Exception;

/**
 * Class UploadGrade
 * @package App\Service\User
 * step1:判断分数是否是符合设定需求的分数
 * step2:根据article_id 、id(check_id)找到check表中的up_id、active_id
 * step3:将分数构成一个序列化的数组存储到check表中的grade字段
 * step4:将总分存储到Sys_grade表中
 */
class UploadGrade extends AbstractService
{
    protected function execute()
    {
        $grade = [-2, -1, 0, 1, 2];
        if (!in_array($this->original_grade, $grade)
            || !in_array($this->format_grade, $grade)
            || !in_array($this->content_grade, $grade)
            || !in_array($this->reference_grade, $grade)
            || !in_array($this->whole_grade, $grade)) {
            throw new Exception('非法的分数值',400);
        }
        $check = new Sys_check(['check_id' => $this->id, 'article_id' => $this->article_id, 'agree' => Sys_check::STATUS_AGREE]);
        if (!$check->exist()) {
            throw new Exception('该记录为空，不能进行评分', 400);
        }
        if($check->state == Sys_check::STATE_FINISH) {
            throw new Exception('你已经完成此文章的审核工作，请勿重复提交', 200);
        }
        $total = $this->original_grade + $this->format_grade + $this->content_grade + $this->reference_grade + $this->whole_grade;
        $result = [
            'original_grade' => $this->original_grade,
            'format_grade' => $this->format_grade,
            'content_grade' => $this->content_grade,
            'reference_grade' => $this->reference_grade,
            'whole_grade' => $this->whole_grade,
            'total' => $total,
        ];
        $grade = serialize($result);
        $check->grade = $grade;
        $check->state = Sys_check::STATE_FINISH;
        $check->save();
        $sys_grade = new Sys_grade(['up_id' => $check->up_id , 'active_id' => $check->active_id]);
        if($sys_grade->exist()) {
            $sys_grade->grade = ($sys_grade + $total) / 2;
            $sys_grade->save();
        } else {
            $sys_grade->import(['up_id' => $check->up_id,
                'active_id' => $check->active_id,
                'grade' => $total])->save();
        }
    }
}