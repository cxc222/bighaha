<?php

namespace Admin\Controller;

class CloudController extends AdminController
{

    public function index()
    {
        $this->display();
    }

    public function install()
    {
        $aToken = I('post.token', '', 'text');
        $aCookie = I('post.cookie', '', 'text');
        $_SESSION['cloud_cookie'] = $aCookie;
        $this->display();
        set_time_limit(0);
        $this->write('自动安装程序开始......<br/>开始获取版本信息......', 'info');
        $this->write('&nbsp;&nbsp;&nbsp;>连接远程服务器....', 'info');
        //   $this->writeMessage(file_get_contents($this->url(cloudU('Appstore/Install/getVersion'))));
        $data = $this->curl($this->url(cloudU('Appstore/Install/getVersion', array('token' => $aToken))));
        if ($data === 'false') {

            $this->write('&nbsp;&nbsp;&nbsp;>服务器登陆验证失败。安装意外终止。', 'danger');
            return;
        }
        $data = json_decode($data, true);
        if (!$data['status']) {
            $this->write('解析服务器返回结果失败。安装意外终止。' . $data['info'], 'danger');
        }
        $version = $data['version'];
        switch ($version['goods']['entity']) {
            case 2:
                $this->installModule($version, $aToken);
                break;
            case 3:
                $this->installTheme($version, $aToken);
                break;
        }


    }

    private function installTheme($version, $token)
    {
        $theme['name'] = $version['goods']['etitle'];
        $theme['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>当前正在安装的是【主题】，主题名【' . $theme['alias'] . '】【' . $theme['name'] . '】');
        if (file_exists(OS_THEME_PATH . $version['goods']['etitle'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>本地已存在同名主题，安装被强制终止。请删除本地主题之后刷新本页面重试。', 'danger');
            return;
        }
        //下载文件
        $localPath = OS_THEME_PATH;
        $localFile = $localPath . $version['goods']['etitle'] . '.zip';
        $this->downloadFile($this->url(cloudU('Appstore/Index/download', array('token' => $token))), $localPath . $version['goods']['etitle'] . '.zip');
        chmod($localFile, 0777);
        //开始安装
        require_once("./ThinkPHP/Library/OT/PclZip.class.php");
        $archive = new \PclZip($localFile);
        $this->write('开始安装主题......');
        $this->write('&nbsp;&nbsp;&nbsp;>开始解压安装包......');
        $list = $archive->extract(PCLZIP_OPT_PATH, $localPath);
        $this->write('&nbsp;&nbsp;&nbsp;>解压成功。', 'success');
        //todo 进行文件合法性检测，防止错误安装。
        $this->write('&nbsp;&nbsp;&nbsp;>安装主题成功。', 'success');
        $themeModel = D('Common/Theme');
        $res = $themeModel->setTheme($theme['name']);
        if ($res === true) {
            // $this->write($moduleModel->getError());
            $this->write('&nbsp;&nbsp;&nbsp;>主题使用成功。', 'success');
            $this->write('主题安装成功，即将跳转到本地主题页面。', 'success');
            $jump = U('Theme/tpls');
            sleep(2);
            $this->writeScript(<<<str
        location.href="$jump";
str
            );
        } else {
            $this->write('&nbsp;&nbsp;&nbsp;>主题使用失败，错误信息：' . $themeModel->getError(), 'danger');
            return;
        }


    }

    private function installModule($version, $token)
    {
        $module['name'] = $version['goods']['etitle'];
        $module['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>当前正在安装的是【模块】，模块名【' . $module['alias'] . '】【' . $module['name'] . '】');
        if (file_exists(APP_PATH . '/' . $version['goods']['etitle'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>本地已存在同名模块，安装被强制终止。请删除本地模块之后刷新本页面重试。', 'danger');
            return;
        }
        //下载文件
        $localPath = APP_PATH . '/';
        $localFile = $localPath . $version['goods']['etitle'] . '.zip';
        $this->downloadFile($this->url(cloudU('Appstore/Index/download', array('token' => $token))), APP_PATH . '/' . $version['goods']['etitle'] . '.zip');
        chmod($localFile, 0777);
        //开始安装
        require_once("./ThinkPHP/Library/OT/PclZip.class.php");
        $archive = new \PclZip($localFile);
        $this->write('开始安装模块......');
        $this->write('&nbsp;&nbsp;&nbsp;>开始解压安装包......');
        $list = $archive->extract(PCLZIP_OPT_PATH, $localPath);
        $this->write('&nbsp;&nbsp;&nbsp;>解压成功。', 'success');
        //todo 进行文件合法性检测，防止错误安装。
        $moduleModel = D('Common/Module');
        $moduleModel->reload();
        $module = $moduleModel->getModule($module['name']);
        $res = $moduleModel->install($module['id']);
        if ($res === true) {
            $this->write($moduleModel->getError());
            $this->write('&nbsp;&nbsp;&nbsp;>安装模块成功。', 'success');
            M('Channel')->where(array('url' => $module['entry']))->delete();
            $this->write('&nbsp;&nbsp;&nbsp;>清理原有的模块导航成功。', 'success');
            $channel['title'] = $module['alias'];
            $channel['url'] = $module['entry'];
            $channel['sort'] = 100;
            $channel['status'] = 1;
            $channel['icon'] = $module['icon'];
            M('Channel')->add($channel);
            S('common_nav', null);
            $this->write('&nbsp;&nbsp;&nbsp;>导航添加成功。', 'success');
            $this->write('模块安装成功，即将跳转到本地模块页面。', 'success');
            $jump = U('Module/lists');
            sleep(2);
            $this->writeScript(<<<str
        location.href="$jump";
str
            );
        } else {
            $this->write('模块安装失败。错误信息：' . $moduleModel->getError(), 'warning');
        }


    }

    private function downloadFile($url, $local)
    {
        $file = fopen($url, "rb");
        if ($file) {
            //获取文件大小
            $filesize = -1;
            $headers = get_headers($url, 1);
            if ((!array_key_exists("Content-Length", $headers))) $filesize = 0;
            $filesize = $headers["Content-Length"];

            //不是所有的文件都会先返回大小的，有些动态页面不先返回总大小，这样就无法计算进度了
            if ($filesize != -1) {
                $this->write('&nbsp;&nbsp;&nbsp;>文件总大小—' . number_format($filesize / 1024, 2) . 'KB');
                $this->write('&nbsp;&nbsp;&nbsp;>开始下载文件');
                $this->showProgress();
            }
            $newf = fopen($local, "wb");
            $downlen = 0;
            $total = 0;
            if ($newf) {
                while (!feof($file)) {
                    $data = fread($file, 1024 * 8);//默认获取8K
                    $downlen += strlen($data);//累计已经下载的字节数
                    fwrite($newf, $data, 1024 * 8);
                    $total += 1024 * 8;
                    if ($total > 1024 * 1024 * 2) {
                        $total = 0;
                        $this->setValue('"' . number_format($downlen / $filesize * 100, 2) . '%' . '"');
                        $this->replace('&nbsp;&nbsp;&nbsp;>已经下载' . number_format($downlen / $filesize * 100, 2) . '% - ' . number_format($downlen / 1024 / 1024, 2) . 'MB', 'success');
                    }
                }
            }
            if ($file) {
                fclose($file);
            }
            if ($newf) {
                fclose($newf);
            }
            $this->replace('&nbsp;&nbsp;&nbsp;>文件下载完成......', 'success');
            $this->hideProgress();
        }
    }

    private function setValue($val)
    {
        $js = <<<str
progress.setValue($val)
str;
        $this->writeScript($js);
    }

    private function showProgress()
    {
        $js = <<<str
        progress.show();
str;
        $this->writeScript($js);
    }

    private function hideProgress()
    {
        $js = <<<str
        progress.hide();
str;
        $this->writeScript($js);
    }

    private function url($url)
    {
        return C('__CLOUD__') . $url;
    }

    private function writeMessage($str)
    {
        $js = <<<str
writeMessage('$str')
str;
        $this->writeScript($js);
    }

    private function replaceMessage($str)
    {
        $js = <<<str
replaceMessage('$str')
str;
        $this->writeScript($js);
    }

    private function writeScript($str)
    {
        echo <<<str
<script>$str</script>
str;
        ob_flush();
        flush();
    }

    private function replace($str, $type = 'info', $br = '<br>')
    {
        $this->replaceMessage(<<<str
<span class="text-$type">$str</span>$br
str
        );
    }

    private function write($str, $type = 'info', $br = '<br>')
    {
        $this->writeMessage(<<<str
<span class="text-$type">$str</span>$br
str
        );
    }


    private function curl($url)
    {
        $cookie_file = 'Runtime/cookie.txt';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11');
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_COOKIE, $this->getCookie(array('PHPSESSID' => $_SESSION['cloud_cookie'])));
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    private function getCookie($cookies)
    {

        $cookies_string = '';
        foreach ($cookies as $name => $value) {
            $cookies_string .= $name . '=' . $value . ';';
        }
        return $cookies_string;
    }

} 