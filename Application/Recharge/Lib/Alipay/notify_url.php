<?php
require_once("lib/alipay_core.function.php");

$root = str_replace('/Application/Recharge/Lib/Alipay/notify_url.php','',$_SERVER['PHP_SELF']);
$url =  'http://'.$_SERVER['HTTP_HOST']. $root.'/index.php?s=/recharge/ali/notify';
logResult($url);
$data = http_build_query($_POST);
ini_set('max_execution_time', '0');
$ch = curl_init();
$header = array(
    'Content-Type: application/x-www-form-urlencoded; charset=' . strtoupper('utf-8') . '',
    'Content-Length: ' . strlen($data)
);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$cookieJar = null;
// 带cookie请求服务器
curl_setopt($ch, CURLOPT_COOKIEFILE, '');
// 保存服务器发送的cookie
$cookieJar = tempnam('tmp', 'cookie');
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$return_content = curl_exec($ch);
$return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
logResult($return_content);
if ($return_content == 'error') {
    echo "fail";
}
echo "success"; //请不要修改或删除


?>