<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/Classes/Meal.php");

use CodeCanna\Meal\Meal;
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
    // Get request method
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
            $reply->data = Meal::getMealByMealName($redis, $_GET['mealName']);
            var_dump($reply->data);
        } catch (Exception | TypeError | InvalidArgumentException | Predis\Response\ServerException $exception) {
            $exceptionType = get_class($exception);
            throw new $exceptionType($exception->getMessage());
        }

        // What to do if request is POST
    } else if ($method === 'POST') {
        try {
            // Create DateTime object from Date string
            $mealDateString = date($_POST['mealDate']);
            $mealDate = new DateTime($mealDateString);

            // Create new Meal
            $meal = new Meal(Uuid::uuid4()->toString(), $_POST['mealName'], $_POST['mealType'], $mealDate, $_POST['mealIngr'], $_POST['calorieCount']);
            
            // Insert Object Values into Redis
            
            /*
            Due the design of RedisDB these lists or tables if you will, must be accessed through a numbered index.
            Key Order:
                0: calorieCount
                1: mainIngredients
                2: mealDate
                3: mealType
                4: mealName
            */
            $redis->lpush($meal->getMealName(), $meal->getMealId(), $meal->getMealName(), $meal->getMealType(), $meal->getMealDate()->format('Y-m-d'), $meal->getMainIngredients(), $meal->getCalorieCount());
            
            // Save meal to disk
            $redis->bgsave();
            if(!$redis->lastsave()) {
                echo "Couldn't save your selection.";
            }
            echo "Save Successful!";

        } catch (Predis\Response\ServerException $exception) {
            $exceptionType = get_class($exception);
            throw (new $exceptionType($exception->getMessage()));
        }
    }
} catch (Exception $exception) {
    $exceptionType = get_class($exception);
    throw new $exceptionType($exception->getMessage());
} catch (TypeError $exception) {
    $exceptionType = get_class($exception);
    throw new $exceptionType($exception->getMessage());
}
