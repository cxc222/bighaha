<?php

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;


class CloudController extends AdminController
{

    public function index()
    {
        $this->display();
    }

    /**
     * 系统自动更新，开始
     */
    public function update()
    {
        $versionModel = D('Version');
        $versionModel->refreshVersions();

        $version = $versionModel->order('number desc')->select();

        $currentVersion = $versionModel->getCurrentVersion();

        foreach ($version as $key => $vo) {
            $versionCompare = version_compare($currentVersion['name'], $vo['name']);
            if ($versionCompare > -1) {
                if ($versionCompare == 0) {
                    $version[$key]['class'] = 'active';
                    $version[$key]['word'] = '【当前版本】';
                } else {
                    $version[$key]['class'] = 'default';
                    $version[$key]['word'] = '【历史版本】';
                }
            } else {
                $version[$key]['class'] = 'success';
                $version[$key]['word'] = '【可升级】';
            }
        }


        $this->assign('cloud', C('__CLOUD__'));
        $this->assign('currentVersion', $currentVersion['name']);
        $this->assign('version', $version);
        $this->assign('nextVersion',$versionModel->getNextVersion());
        $this->disableCheckUpdate();
        $this->display();
    }

    private function disableCheckUpdate(){
        $this->assign('update', false);
    }
    /**
     * 获取文件列表
     */
    public function getFileList()
    {


        $aVersion = I('get.version', '', 'text');
        if ($aVersion == '') {
            $this->error('升级失败，请确认版本。');
        }
        $versionModel = D('Version');
        $nextVersion = $versionModel->getNextVersion();
        if ($aVersion != $nextVersion['name']) {
            $this->error('此版本不允许当前版本升级，请不要跳过中间版本。');
        }
        $this->assign('path', C('UPDATE_PATH') . $nextVersion['name']);
        /*版本正确性检测↑*/
        $currentVersion = $versionModel->getCurrentVersion();
        $this->assign('currentVersion', $currentVersion);
        $this->assign('nextVersion', $nextVersion);
        $this->disableCheckUpdate();
        $this->display();;
        $this->writeMessage('开始下载原版文件包。<br/>');

        set_time_limit(0);
        @mkdir(C('UPDATE_PATH') . $nextVersion['name']);
        $old_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/old';

        $new_file_path = C('UPDATE_PATH') . $nextVersion['name'] . '/new';
        $this->downloadFile(C('__CLOUD__') . cloudU('Appstore/Update/download', array('number' => $nextVersion['number'], 'type' => 'old')), C('UPDATE_PATH') . $nextVersion['name'] . '/old.zip');
        $this->unzipFile(C('UPDATE_PATH') . $nextVersion['name'] . '/old.zip', $old_file_path);

        $this->writeMessage('开始下载升级文件包。<br/>');
        $this->downloadFile(C('__CLOUD__') . cloudU('Appstore/Update/download', array('number' => $nextVersion['number'], 'type' => 'new')), C('UPDATE_PATH') . $nextVersion['name'] . '/new.zip');
        $this->unzipFile(C('UPDATE_PATH') . $nextVersion['name'] . '/new.zip', $new_file_path);
        $files = $this->treeDirectory($new_file_path, $new_file_path);
        foreach ($files as $v) {
            $this->writeFile($v);
        }
        $this->writeScript('enable()');
        $_SESSION['nextVersion'] = $nextVersion;
        $_SESSION['currentVersion'] = $currentVersion;
    }

    /**
     * 比对代码
     */
    public function compare()
    {
        $this->assignVersionInfo();
        $old_file_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'] . '/old';
        $new_file_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'] . '/new';
        $compared_with_old = $this->diff($old_file_path);
        $compared_with_new = $this->diff($new_file_path);
        $compared = $compared_with_old + $compared_with_new;

        $this->assign('path', C('UPDATE_PATH') . $_SESSION['currentVersion']['name']);
        $this->assign('compared', $compared);
        $this->disableCheckUpdate();
        $this->display();
        foreach ($compared as $key => $v) {
            $this->writeFile(<<<str
            $key   …… {$this->convert($v)}
str
            );
        }
    }

    /**
     * 覆盖代码
     */
    public function cover()
    {
        $this->assignVersionInfo();
        $old_file_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'] . '/old';
        $new_file_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'] . '/new';
        $sub=date('Ymd-His');
        $backup_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'] . '/backup/'.$sub;
        $this->assign('backup_path',$backup_path);
        $need_back = $this->treeDirectory($new_file_path, $new_file_path);
        $this->disableCheckUpdate();
        $this->display();
        //备份文件
        @mkdir(C('UPDATE_PATH') . $_SESSION['nextVersion']['name'] . '/backup');
        @mkdir($backup_path);
        foreach ($need_back as $v) {
            if(text($v)=='/update.sql'){
                continue;
            }
            $from=realpath('.' . text($v));
            $des = realpath(str_replace('./', '', $backup_path)) . str_replace('/', DIRECTORY_SEPARATOR, text($v));
            $des_dir = substr($des, 0, strrpos($des, '\\'));
            $this->CreateFolder($des_dir);
            copy($from, $des);
            $this->write(str_replace('\\','\\\\','备份文件 '. text($v).' 到 '.str_replace('./', '', $backup_path). text($v)).'……成功','success');

        }
        $this->write('文件全部备份完成。');
        //覆盖文件

        foreach($need_back as $v){

            $from=realpath( $new_file_path.text($v));
            $des=realpath('.'.str_replace('/', DIRECTORY_SEPARATOR,text($v)));

            if(!$des){
                $des=str_replace('/',DIRECTORY_SEPARATOR,dirname(realpath('./index.php')).text($v));
            }
            $des_dir = substr($des, 0, strrpos($des, '\\'));
            if(!is_dir($des_dir)){
                $this->CreateFolder($des_dir);
            }
            if(file_exists($des)){
                unlink($des);
            }
            if(copy($from, $des)){
                $this->writeFile(str_replace('\\','\\\\','覆盖文件'.$des).'……成功');
            }else{
                $this->writeFile(str_replace('\\','\\\\','覆盖文件'.$des).'……失败');
            }


        }
        $this->write('文件全部覆盖完成。');
        $this->writeScript('enable()');
    }

    /**
     * 升级数据库
     */
    public function updb(){

        $new_file_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'];
        $sql_path=$new_file_path. '/new/update.sql';
        $sql=file_get_contents($sql_path);
        if(IS_POST){
            if(!file_exists($sql_path))
            {
                $this->error('数据库升级脚本不存在。');
            }else{
                $result=D('')->executeSqlFile($sql_path);
                if($result){
                    $this->success('脚本升级成功。');
                }else{
                    $this->error('脚本升级失败。');
                }
            }
        }else{
            $this->assignVersionInfo();
            $this->assign('path',$new_file_path);
            if(file_exists($sql_path)){
                $this->assign('sql',$sql);
            }
            $this->disableCheckUpdate();
            $this->display();
        }

    }

    public function finish(){
        $nextVersion=$_SESSION['nextVersion'];
        $currentVersion=$_SESSION['currentVersion'];
        $versionModel=D('Version');
        $versionModel->where(array('name'=>$nextVersion['name']))->setField('update_time',time());
        $versionModel->setCurrentVersion($nextVersion['name']);
        $this->assign('currentVersion',$versionModel->getCurrentVersion());
        $new_file_path = C('UPDATE_PATH') . $_SESSION['nextVersion']['name'];
        $this->assign('path',$new_file_path);
        $this->disableCheckUpdate();
        $versionModel->cleanCheckUpdateCache();
        $this->display();
    }

    /**递归方式创建文件夹
     * @param $dir
     * @param int $mode
     * @return bool
     */
    private function CreateFolder($dir, $mode = 0777)
    {
        if (is_dir($dir) || @mkdir($dir, $mode)) {
            return true;
        }
        if (!$this->CreateFolder(dirname($dir), $mode)) {
            return false;
        }
        return @mkdir($dir, $mode);
    }

    private function convert($v)
    {
        switch ($v) {
            case 'add':
                return '<span class="text-warning"> <i class="icon-plus"></i> 新增，新版本新增的文件</span>';
                break;
            case 'modified':
                return '<span class="text-danger" ><i class="icon-warning-sign"></i> 冲突，二次开发修改，未通过</span>';
                break;
            case 'ok':
                return '<span class="text-success"><i class="icon-check"></i> OK，和原版一样，通过</span>';
            case 'db':
                return '<span class="text-info"><i class="icon-cube"></i> 数据库引导文件，通过</span>';
            case 'guide':
                return '<span class="text-info"><i class="icon-cube"></i> 引导脚本，通过</span>';

        }
    }

    /**比较文件
     * @param $path
     * @return array
     */
    private function diff($path)
    {
        $files = $this->treeDirectory($path, $path);
        $result = array();
        foreach ($files as $v) {
            if(text($v)=='/update.sql'){
                $result[$v]='db';
                continue;
            }else if(text($v)=='/update.php'){
                $result[$v]='guide';
                continue;
            }
            $md5_source = md5_file($path . text($v));
            $md5_local = md5_file('./' . text($v));
            if (!$md5_local) {
                $result[$v] = 'add';
            } else if ($md5_source != $md5_local) {
                $result[$v] = 'modified';
            } else {
                $result[$v] = 'ok';
            }
        }
        return $result;
    }

    /**列出所有的文件
     * @param $dir
     * @param $root
     * @return array
     */
    private function treeDirectory($dir, $root)
    {
        $files = array();
        $dirpath = ($dir);
        $filenames = scandir($dir);

        foreach ($filenames as $filename) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }

            $file = $dirpath . DIRECTORY_SEPARATOR . $filename;

            if (is_dir($file)) {
                $files = array_merge($files, $this->treeDirectory($file, $root));
            } else {
                $files[] = str_replace($root, '', str_replace('\\', '/', $dir . DIRECTORY_SEPARATOR . '<span class=text-success>' . $filename . '</span>'));
            }
        }

        return $files;
    }

    /**
     * 获取指定版本的信息
     */
    public function version()
    {
        $aName = I('get.name', '', 'text');
        $versionModel = D('Version');
        $version = $versionModel->where(array('name' => $aName))->find();
        $this->assign('log', nl2br($version['log']));
        $this->display('version');

    }

    /**
     * 安装程序
     */
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
            case 1:
                $this->installPlugin($version, $aToken);
                break;
            case 2:
                $this->installModule($version, $aToken);
                break;
            case 3:
                $this->installTheme($version, $aToken);
                break;
        }
    }

    private function installPlugin($version, $token)
    {
        $plugin['name'] = $version['goods']['etitle'];
        $plugin['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>当前正在安装的是【插件】，插件名【' . $plugin['alias'] . '】【' . $plugin['name'] . '】');
        if (file_exists(ONETHINK_ADDON_PATH . '/' . $plugin['name'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>本地已存在同名插件，安装被强制终止。请删除本地插件之后刷新本页面重试。3秒后退回到上一页。', 'danger');
            $this->goBack();
            return;
        }
        //下载文件
        $localPath = ONETHINK_ADDON_PATH;
        $localFile = $localPath . $plugin['name'] . '.zip';
        $this->downloadFile($this->url(cloudU('Appstore/Index/download', array('token' => $token))), $localFile);
        chmod($localFile, 0777);
        //开始安装
        $this->write('开始安装插件......');
        $this->unzipFile($localFile, $localPath);
        $rs = D('Addons')->install($plugin['name']);
        if ($rs === true) {
            $this->write('&nbsp;&nbsp;&nbsp;>插件安装成功。即将跳转到本地插件页面。', 'success');
            $jump = U('Addons/index');
            sleep(2);
            $this->writeScript(<<<str
        location.href="$jump";
str
            );
            return;
        } else {
            $this->write('&nbsp;&nbsp;&nbsp;>插件安装失败。', 'danger');
        }

        //todo 进行文件合法性检测，防止错误安装。

    }

    private function installTheme($version, $token)
    {
        $theme['name'] = $version['goods']['etitle'];
        $theme['alias'] = $version['goods']['title'];
        $this->write('&nbsp;&nbsp;&nbsp;>当前正在安装的是【主题】，主题名【' . $theme['alias'] . '】【' . $theme['name'] . '】');
        if (file_exists(OS_THEME_PATH . $version['goods']['etitle'])) {
            //todo 进行版本检测
            $this->write('&nbsp;&nbsp;&nbsp;>本地已存在同名主题，安装被强制终止。请删除本地主题之后刷新本页面重试。3秒后退回到上一页。', 'danger');
            $this->goBack();
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
            $jump = U('Theme/tpls', array('cleanCookie' => 1));
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
            $this->write('&nbsp;&nbsp;&nbsp;>本地已存在同名模块，安装被强制终止。请删除本地模块之后刷新本页面重试。3秒后回到上一页。', 'danger');
            $this->goBack();
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

    private function writeFile($str)
    {
        $js = <<<str
writeFile('$str')
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

    private function goBack()
    {
        $this->writeScript(<<<str
  setTimeout(function(){
            history.go(-1);
        },3000);
str
        );
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

    /**
     * @param $localFile
     * @param $localPath
     */
    private function unzipFile($localFile, $localPath)
    {
        require_once("./ThinkPHP/Library/OT/PclZip.class.php");
        $archive = new \PclZip($localFile);

        $this->write('&nbsp;&nbsp;&nbsp;>开始解压文件......');
        $list = $archive->extract(PCLZIP_OPT_PATH, $localPath);
        $this->write('&nbsp;&nbsp;&nbsp;>解压成功。', 'success');
    }

    private function assignVersionInfo()
    {
        $currentVersion = $_SESSION['currentVersion'];
        $nextVersion = $_SESSION['nextVersion'];
        $this->assign('nextVersion', $nextVersion);
        $this->assign('currentVersion', $currentVersion);
    }

} 