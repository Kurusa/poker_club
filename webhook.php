<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once(__DIR__ . '/bootstrap.php');

try {
    (new App\WebhookController())->handle();
} catch (Exception $e) {
    error_log(json_encode($e));
    return true;
}
