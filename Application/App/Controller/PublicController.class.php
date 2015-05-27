<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 1/15/14
 * Time: 4:17 PM
 */

namespace App\Controller;

use Think\Controller;


class PublicController extends BaseController
{




    /**上传图片
     * @auth 陈一枭
     */
    public function uploadImage()
    {
        $this->requireLogin();
        /* 返回标准数据 */
        $return = array('status' => 1, 'info' => '上传成功', 'data' => '');
        //实际有用的数据只有name和state，这边伪造一堆数据保证格式正确
        $originalName = '';
        $type = '.jpg';
        $rs = array(
            "originalName" => $originalName,
            'name' => '',
            'url' => '',
            'size' => '',
            'type' => $type,
            'state' => 'success',
            'id' => 0
        );
        /* 调用文件上传组件上传文件 */
        $Picture = D('Admin/Picture');
        $pic_driver = C('PICTURE_UPLOAD_DRIVER');

        $info = $Picture->upload(
            $_FILES,
            C('PICTURE_UPLOAD'),
            C('PICTURE_UPLOAD_DRIVER'),
            C("UPLOAD_{$pic_driver}_CONFIG")
        );

        /* 记录图片信息 */
        if ($info) {
            $return['status'] = 1;
            if ($info['Filedata']) {
                $return = array_merge($info['Filedata'], $return);
            }
            if ($info['download']) {
                $return = array_merge($info['download'], $return);
            }
            $rs['state'] = 'SUCCESS';
            $rs['url'] = $info['image']['path'];
            $rs['id'] = $info['image']['id'];
            if ($type == 'ajax') {
                echo json_encode($rs);
                exit;
            } else {
                echo json_encode($rs);
                exit;
            }
        } else {
            $return['state'] = 0;
            $return['info'] = $Picture->getError();
        }

    }


    /**检查新版本
     * @param $version 当前版本
     * @auth 陈一枭
     */
    public function checkUpdate($version)
    {
        if ($version != C('APP_VERSION')) {
            $this->apiSuccess('有新版本', array('file' => C('APP_FILE')));
        } else {
            $this->apiError('没有新版本', 8000);
        }
    }

    /**签到
     * @auth 陈一枭
     */
    public function checkin()
    {
        $this->requireLogin();

        $uid = is_login();

        $map['ctime'] = array('egt', strtotime(date('Ymd')));
        $map['uid'] = $uid;
        $ischeck = D('Check_info')->where($map)->find();

        //是否重复签到

        if (!$ischeck) {
            $map_last['ctime'] = array('lt', strtotime(date('Ymd')));
            $map_last['uid'] = $uid;
            $last = D('Check_info')->where($map_last)->order('ctime desc')->find();
            $data['ctime'] = $_SERVER['REQUEST_TIME'];

            $add_score= modC('User_CheckIN_Score', '0', 'user');
            //是否有签到记录
            if ($last) {
                //是否是连续签到
                if ($last['ctime'] >= (strtotime(date('Ymd')) - 86400)) {
                    $data['con_num'] = $last['con_num'] + 1;
                } else {
                    $data['con_num'] = 1;
                }
                $data['total_num'] = $last['total_num'] + 1;
                $data['total_score']=$last['total_score']+$add_score;
                $result=D('Check_info')->where(array('uid'=>$uid))->save($data);
            } else {
                $data['uid'] = $uid;
                $data['con_num'] = 1;
                $data['total_num'] = 1;
                $data['total_score']=$add_score;
                $result=D('Check_info')->add($data);
            }
            if ($result) {
                S('check_rank', null);
                //更新连续签到和累计签到的数据
                $this->apiSuccess('签到成功。', array(
                    'con_num' => $data['con_num'],
                    'total_num' => $data['total_num'],
                    'over_rate' => $this->getOverRate($data['total_num'])
                ));

            }
        } else {
            $this->apiError('已签到。', 8000);
        }
    }

    /**获取签到信息
     * @auth 陈一枭
     */
    public function getCheckInfo()
    {
        //TODO 缓存
        $this->requireLogin();
        $uid = is_login();
        $map['uid'] = $uid;
        $last = D('Check_info')->where($map)->order('ctime desc')->find();

        $map['ctime'] = array('gt', strtotime(date('Ymd')));
        $ischeck = D('Check_info')->where($map)->count();

        //是否重复签到

        //是否有签到记录
        if ($last) {

            //更新连续签到和累计签到的数据
            $this->apiSuccess('获取成功。', array(
                'con_num' => $last['con_num'],
                'total_num' => $last['total_num'],
                'over_rate' => $this->getOverRate($last['total_num']),
                'has_checked' => $ischeck,
            ));
        } else {
            //更新连续签到和累计签到的数据
            $this->apiSuccess('获取成功。', array(
                'con_num' => 0,
                'total_num' => 0,
                'over_rate' => 0
            ));
        }


    }

    /**获取签到排名
     * @auth 陈一枭
     */
    public function getCheckRank()
    {

        $getranktime = get_addon_config('Rank_checkin');
        $set_ranktime = $getranktime['ranktime'];

        $y = date("Y", time());
        $m = date("m", time());
        $d = date("d", time());

        $start_time = mktime($set_ranktime, 0, 0, $m, $d, $y);
        $this->assign("ss", $start_time);
        $rank = S('check_rank');
        if (empty($rank)) {
            $rank = D('Check_info')->where('ctime>' . $start_time)->order('ctime asc')->limit(5)->select();
            S('check_rank', $rank, 60);
        }

        if (time() <= $start_time) {
            $return = array('time' => $start_time);
        } else {
            foreach ($rank as &$v) {
                $v['userInfo'] = query_user(array('avatar128', 'space_url', 'nickname', 'uid',), $v['uid']);
            }
            $return = array('list' => $rank);
        }

        if (isset($return['time'])) {
            $this->apiError( '签到还未开始。','8000', $return);
        } else {
            $this->apiSuccess('获取成功。', $return);
        }
    }

    /**获取签到超过的比例
     * @param $total_num
     * @return string
     * @auth 陈一枭
     */
    private function getOverRate($total_num)
    {
        $db_prefix = C('DB_PREFIX');
        $over_count = D()->query("select count(uid)  AS rank from (SELECT *,max(total_num) as total FROM `{$db_prefix}check_info`  WHERE 1 group by uid ) as checkin where total>{$total_num}");

        $users_count = D('Member')->count('uid');
        $over_rate = ((1 - number_format($over_count[0]['rank'] / $users_count, '3')) * 100) . "%";
        return $over_rate;
    }
}