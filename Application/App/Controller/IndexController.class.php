<?php

namespace App\Controller;

use Think\Controller;

/**
 * Class IndexController
 * @package App\Controller
 * @auth 陈一枭
 */
class IndexController extends Controller
{

    /**提示部署成功
     * @auth 陈一枭
     */
    public function index()
    {
        $this->show('App部署成功。');
    }

}