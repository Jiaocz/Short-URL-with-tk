<?php
/**
 * Return Code.
 * 100: OK
 * 201: 站点关闭
 * 202: GET方法被关闭
 * 203: 到注册商的API出现错误
 */
require_once("config.php");

/**
 * The function to query the shorten URL.
 * @author Orangii
 * @version 1.0.0
 * @param $longURL The URL to be shorten.
 * @return String The result URL(domain name). or {@code false} if failed.
 */
function getShortenURL($longURL){
    $PostData = array(
        'forward_url' => $longURL,
        'email' => ACCOUNT_EMAIL,
        'password' => ACCOUNT_PASSWORLD
    );

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.freenom.com/v2/domain/register.json');
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($PostData));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);

    return json_decode($result);
}

if(!SITE_ENABLED){
    $result = array(
        'status' => 'failed',
        'code' => '201',
        'message' => 'Site has been closed.'
    );

    echo json_encode($result);
    exit(201);
}

$shortdomain;

if(isset($_GET['longurl'])){
    if(!API_GET_ENABLED){
        $result = array(
            'status' => 'failed',
            'code' => '202',
            'message' => 'GET Method is not enabled on this site.'
        );

        echo json_encode($result);
        exit(202);
    }
    else{
        $shortdomain = getShortenURL($_GET['longurl']);
    }
}

if(isset($_POST['longurl']) && $shortdomain == null){
    $shortdomain = getShortenURL($_POST['longurl']);
}

if($shortdomain->status == 'OK' && !isset($shortdomain->error)){
    $result = array(
        'status' => 'success',
        'domain' => $shortdomain['domain'][0]['domainname'],
        'code' => '100'
    );

    echo json_encode($result);
}
else {
    $result = array(
        'status' => 'failed',
        'code' => '203',
        'message' => $shortdomain->error
    );

    echo json_encode($result);
}