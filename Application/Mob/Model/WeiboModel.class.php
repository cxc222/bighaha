<?php
namespace Mob\Model;

use Think\Model;
use Think\Hook;

require_once('./Application/Mob/Common/function.php');
class WeiboModel extends Model
{


    public function addWeibo($uid, $content = '', $type = 'feed', $feed_data = array(), $from = '')
    {
//写入数据库
        //  dump($uid);exit;
        $create_time=time();
        $data = array('uid' => $uid, 'content' => $content, 'type' => $type, 'create_time'=>$create_time, 'data' => serialize($feed_data), 'from' => $from,'status'=>1);
        if (!$data) return false;
        $weibo_id = $this->add($data);

//返回微博编号
        return $weibo_id;
    }





    public function getWeiboDetail($id)
    {
        $weibo = S('weibo_' . $id);
        if (empty($weibo)) {
            $weibo = $this->where(array('status' => 1, 'id' => $id))->find();
            if (!$weibo) {
                return false;
            }

            $weibo_data = unserialize($weibo['data']);              //源微博是atid_id，转发微博是sourse和sourseID数组
            $class_exists = true;
            $type = array('repost', 'feed', 'image');

            if (!in_array($weibo['type'], $type)) {
                $class_exists = class_exists('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon');
            }

            $weibo['content'] = parse_topic(parse_weibo_content($weibo['content']));

/*            if ($weibo['type'] === 'feed' || $weibo['type'] == '' || !$class_exists) {
                $fetchContent = "<p class='word-wrap'>" . $weibo['content'] . "</p>";

            } elseif ($weibo['type'] === 'repost') {
                $fetchContent = A('Weibo/Type')->fetchRepost($weibo);
            } elseif ($weibo['type'] === 'image') {
                $fetchContent = A('Weibo/Type')->fetchImage($weibo);
            } else {
                $fetchContent = Hook::exec('Addons\\Insert' . ucfirst($weibo['type']) . '\\Insert' . ucfirst($weibo['type']) . 'Addon', 'fetch' . ucfirst($weibo['type']), $weibo);
            }*/


            $weibo = array(
                'id' => intval($weibo['id']),
                'content' => strval($weibo['content']),
                'create_time' => intval($weibo['create_time']),
                'type' => $weibo['type'],
                'data' => unserialize($weibo['data']),
                'weibo_data' => $weibo_data,              //
                'comment_count' => intval($weibo['comment_count']),
                'repost_count' => intval($weibo['repost_count']),
                'can_delete' => 0,
                'user' => query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'icons_html', 'rank_link', 'score', 'title', 'weibocount', 'fans', 'following'), $weibo['uid']),
                'is_top' => $weibo['is_top'],
                'uid' => $weibo['uid'],
              //  'fetchContent' => $fetchContent,
                'from' => $weibo['from']

            );

            S('weibo_' . $id, $weibo, 60 * 60);
        }
        $weibo['can_delete'] = $this->canDeleteWeibo($weibo);
        return $weibo;
    }


    private function canDeleteWeibo($weibo)
    {
        //如果是管理员，则可以删除微博
        if (is_administrator(get_uid()) || check_auth('deleteWeibo')) {
            return true;
        }

        //如果是自己发送的微博，可以删除微博
        if ($weibo['uid'] == get_uid()) {
            return true;
        }

        //返回，不能删除微博
        return false;
    }
}