<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-8-27
 * Time: 下午4:54
 * @author 想天小郑<zzl@ourstu.com>
 */
return array(

    /**
     * 路由的key必须写全称,且必须全小写. 比如: 使用'wap/index/index', 而非'wap'.
     */
    'router' => array(

        /*商城配置*/
        'shop/index/index'          =>  'shop',
        'shop/index/goods'			=>  'goods/[category_id]',
        'shop/index/goodsdetail'    =>  'goods/detail_[id]',
        'shop/index/mygoods'        =>  'mygoods/[status]',

        /*活动*/
        'event/index/index'         => 'event/[type_id]/p_[page]',
        'event/index/myevent'       => 'myevent/[type_id]',
        'event/index/detail'        => 'event/detail_[id]',
        'event/index/member'        => 'event/member_[id]',
        'event/index/edit'          => 'event/edit_[id]',
        'event/index/add'           => 'event/add',

        /*专辑*/
        'issue/index/index'                     => 'issue/[issue_id]/p_[page]',
        'issue/index/issuecontentdetail'        => 'issue/detail_[id]',
        'issue/index/edit'                      => 'issue/edit_[id]',

        /*讨论区*/
        'forum/index/index'                     => 'forum',
        'forum/index/forum'                     => 'forum/[id]/p_[page]',
        'forum/index/edit'                      => 'forum/edit_[forum_id]/p_[post_id]',
        'forum/index/detail'                    => 'forum/detail_[id]',
        'forum/index/search'                    => 'forum/search',

        /*资讯*/
        'blog/index/index'                      => 'blog/p_[page]',
        'blog/article/lists'                    => 'blog/list_[category]',
        'blog/article/detail'                   => 'blog/detail_[id]',

        /*微博*/
        'weibo/index/index'                     => 'weibo/p_[page]',
        'weibo/index/weibodetail'               => 'weibo/detail_[id]',
        'weibo/index/myconcerned'               => 'weibo/concerned',
        'weibo/index/search'                    => 'weibo/search',

        /*用户中心*/
        'ucenter/index/index'                => 'ucenter/[uid]',
        'ucenter/index/following'            => 'ucenter/following_[uid]',
        'ucenter/index/applist'              => 'ucenter/applist_[type]/[uid]',
        'ucenter/index/information'          => 'ucenter/information_[uid]',
        'ucenter/index/fans'                 => 'ucenter/fans_[uid]',
        'ucenter/index/rank'                 => 'ucenter/rank_[uid]',
        'ucenter/index/rankverifywait'       => 'ucenter/rankwait_[uid]',
        'ucenter/index/rankverifyfailure'    => 'ucenter/rankfailure_[uid]',
        'ucenter/index/rankverify'           => 'ucenter/rankverify_[uid]',
        'ucenter/config/index'               => 'ucenter/conf',
        'ucenter/message/session'            => 'ucenter/session',
        'ucenter/message/message'            => 'ucenter/msg',
        'ucenter/message/collection'         => 'ucenter/collection',

        /*会员*/
        'people/index/find'                     => 'people/find',
        'people/index/index'                    => 'people',

        /*注册登录*/
        'home/user/register'                    => 'register',
        'home/user/step2'                       => 'register/step2',
        'home/user/step3'                       => 'register/step3',
        'home/user/login'                       => 'login',
    ),

);