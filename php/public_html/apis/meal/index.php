<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/Classes/Meal.php");

use CodeCann\Meal;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\Stub\Exception;
use Predis\Autoloader;

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    // Create a new redis instance and connect to it
    try {
        $redis = new Predis\Client();
        $redis->connect('127.0.0.1');
        
        // Check the connection by pinging the server
        if ($redis->ping()) {
            echo "Successfully connected to Redis!";
        } else {
            echo "Could not connect to Redis...";
        }
    } catch(Exception | TypeError | InvalidArgumentException $exception) {
        $exceptionType = gettype($exception);
        throw new $exceptionType($exception->getMessage());
    }
    



    if ($method === 'GET') {
        
    }
} catch (Exception $exception) {
    throw new $exception;
} catch (TypeError $exception) {
    throw new TypeError("No");
}
