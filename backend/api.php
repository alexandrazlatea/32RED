<?php

require_once "lib/promo_manager.php";

header("Content-type: application/json");

$login = false;
if ($_GET['action'] == 'removeCookie') {
    setcookie('promo_username', null, -1);
    return;
}
if ($_GET['action'] == 'getUserDetails') {
    $player = new Player($_GET['username']);
     die(json_encode($player->getCurrentPlayer()));
}

if ($_GET['action'] == 'getCookie') {
    if (!empty($_COOKIE['promo_username'])) {
        die(json_encode($_COOKIE['promo_username']));
    }
    return;
}

$requiredParams = array('username', 'promo', 'action');

// Attempt to set up the promo
if (empty($_GET['username'])) {
    $username = (empty($_COOKIE['promo_username'])) ? '' : $_COOKIE['promo_username'];
} else {
    $username = $_GET['username'];
}

if (empty($_GET['promo'])) {
    $promo = (empty($_COOKIE['promo'])) ? 1 : $_COOKIE['promo'];
} else {
    $promo = $_GET['promo'];
}

$promoMgr = new PromoManager($promo, $username);
if (!empty($_GET['option'])) {
    $promoMgr->setOption($_GET['option']);
}
// Check that the passed promo is valid
if (!$promoMgr->isValidPromo()) {
    http_response_code(400);
    die(json_encode(array("error" => "Invalid promotion")));
}
// Check that the passed user is valid
if ($_GET['action'] != 'getPromotion') {
    if (!empty($_GET['login']) || ($_GET['action'] == 'optin')) {
        $login = true;
    }
    if (!$promoMgr->isValidUser($login)) {
        http_response_code(400);
        die(json_encode(array("error" => "Invalid username")));
    } else {
        $promoMgr->saveCookie($_GET['username']);
    }
}    

switch($_GET['action']) {
    case 'status':
        die(json_encode(array(
            "status" => $promoMgr->getStatus()
        )));
        break;
    case 'optin':
        die(json_encode(array(
            "result" => $promoMgr->optIn(),
            "status" => $promoMgr->getStatus()
        )));
        break;
    case 'getPromotion':
        die(json_encode(array(
            "promotion" => $promoMgr->getCurrentPromotion(),
            "status" => $promoMgr->getStatus()
        )));
        break;
   case 'logIn':
        return;
        break;
    default:
        http_response_code(400);
        die(json_encode(array("error" => "Invalid action")));
        break;
}