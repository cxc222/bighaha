<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 陈一枭
 * 创建日期: 5/11/14
 * 创建时间: 9:44 PM
 * 版权所有 嘉兴想天信息科技有限公司(www.ourstu.com)
 */

namespace Common\Model;


/**内容处理模型，专门用于预处理各类文本
 * Class ContentHandlerModel
 * @package Common\Model
 * @auth 陈一枭
 */
class ContentHandlerModel {

    /**处理@
     * @auth 陈一枭
     */
    public function handleAtWho($content,$url='',$app_name='',$escap_first=false){
        $uids = get_at_uids($content);

        $uids = array_unique($uids);
        $sender=query_user(array('nickname'));
        $first=true;
        foreach ($uids as $uid) {
            if($escap_first && $first){
                $first=false;
                continue;
            }
            //$user = UCenterMember()->find($uid);
            $title = $sender['nickname'] . '@了您';
            $message = '评论内容：' . mb_substr(op_t( $content),0,50,'utf-8');
            if($url==''){//如果未设置来源的url，则自动跳转到来源页面
                $url = $_SERVER['HTTP_REFERER'];
            }

            D('Common/Message')->sendMessage($uid, $message, $title, $url, get_uid(), 0, $app_name);
        }
    }

    /**在编辑的时候过滤内容
     * @param $content
     * @return mixed
     */
    public function filterHtmlContent($content){
        $content=html($content);
        $content = filterBase64($content);
        //检测图片src是否为图片并进行过滤
        $content = filterImage($content);
        return $content;
    }

    /**显示html内容,一般用于显示编辑器内容，会加入弹窗和at效果
     * @param $content
     */
    public function displayHtmlContent($content){
        $content=parse_popup($content);
        $content=parse_at_users($content);
        return $content;

    }

    /**限制图片数量
     * @param $content
     * @param int $count
     * @return mixed
     */
    public function limitPicture($content,$count=10){

            //默认最多显示10张图片
            $maxImageCount =$count;
            //正则表达式配置
            $beginMark = 'BEGIN0000hfuidafoidsjfiadosj';
            $endMark = 'END0000fjidoajfdsiofjdiofjasid';
            $imageRegex ='/<img(.*?)\\>/i';
            $reverseRegex = "/{$beginMark}(.*?){$endMark}/i";
            //如果图片数量不够多，那就不用额外处理了。
            $imageCount= preg_match_all($imageRegex, $content,$res);
            if ($imageCount <= $maxImageCount) {
                return $content;
            }
            //清除伪造图片
            $content = preg_replace($reverseRegex, "<img$1>", $content);
            //临时替换图片来保留前$maxImageCount张图片
            $content = preg_replace($imageRegex, "{$beginMark}$1{$endMark}", $content, $maxImageCount);
            //替换多余的图片
            $content = preg_replace($imageRegex, "[图片]", $content);
            //将替换的东西替换回来
            $content = preg_replace($reverseRegex, "<img$1>", $content);
            //返回结果
            return $content;


} }