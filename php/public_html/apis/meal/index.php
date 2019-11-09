<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/Classes/Meal.php");

use CodeCann\Meal;
use PHPUnit\Framework\InvalidArgumentException;
use PHPUnit\Framework\MockObject\Stub\Exception;
use Predis\Autoloader;
use Prophecy\Argument\Token\ExactValueToken;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\InvalidUuidStringException;

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    // Create a new redis instance and connect to it
    $redis = new Predis\Client();
    $redis->connect('127.0.0.1');

    // Check the connection by pinging the server
    try {
        $redis->ping();
    } catch (Predis\Connection\ConnectionException $exception) {
        $exceptionType = get_class($exception);
        throw new $exceptionType($exception->getMessage());
    }

    // What to do if the reqeust is GET
    if ($method === 'GET') {
        try {
            // Get value from redis
            echo $redis->get($_GET['mealNameKey']);
        } catch (Exception | TypeError | InvalidArgumentException | Predis\Response\ServerException $exception) {
            $exceptionType = get_class($exception);
            throw new $exceptionType($exception->getMessage());
        }

        // What to do if request is POST
    } else if ($method === 'POST') {
        try {
            // Get POST values
            foreach ($_POST as $meal) {
                echo $_POST[$meal];
            }
            $redis->set('mealId', $_POST['mealId']);
        } catch (Predis\Response\ServerException $exception) {
            $exceptionType = get_class($exception);
            throw (new $exceptionType($exception->getMessage()));
        }
    }
} catch (Exception $exception) {
    throw new $exception;
} catch (TypeError $exception) {
    throw new TypeError("No");
}
