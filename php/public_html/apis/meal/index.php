<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/Classes/Meal.php");

use CodeCanna\Meal\Meal;
use \InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Predis\Client;

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    // Get request method
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    // Create a new redis instance and connect to it
    $redis = new Client();
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
            // Filter and Sanitize input
            $mealId = filter_input(INPUT_GET, 'mealId', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $mealName = filter_input(INPUT_GET, 'mealName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $mealType = filter_input(INPUT_GET, 'mealType', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $mealDate = filter_input(INPUT_GET, 'mealDate', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUITES);
            $mealIngredients = filter_input(INPUT_GET, 'mainIngredients', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $mealCalorieCount = filter_input(INPUT_GET, 'mealCalorieCount', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            // If no get data is set reply data to all meals
            if(empty($mealName)) {
                $reply->data = Meal::getAllMeals($redis);
            } elseif(!empty($mealName)) {
                try {
                    $reply->data = Meal::getMealByMealName($redis, $mealName);
                } catch(\InvalidArgumentException $exception) {
                    $reply->data = "Meal not found...";
                }
            } elseif(!empty($mealName)) {

            }

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

            $convertedCalorieCount = intval($_POST['calorieCount']);

            // Create new Meal
            $meal = new Meal(Uuid::uuid4()->toString(), $_POST['mealName'], $_POST['mealType'], $mealDate, $_POST['mealIngredients'], $convertedCalorieCount);

            // Insert Object Values into Redis
            /*
            Due the design of RedisDB these lists or tables if you will, must be accessed through a numbered index.
            Key Order:
                0: calorieCount
                1: mainIngredients
                2: mealDate
                3: mealType
                4: mealName
                5: mealId
            */
            $redis->lpush($meal->getMealName(), $meal->getMealId(), $meal->getMealName(), $meal->getMealType(), $meal->getMealDate()->format('Y-m-d'), $meal->getMainIngredients(), $meal->getCalorieCount());

            // Save meal to disk
            $lastLastSave = $redis->lastsave();
            $redis->bgsave();
            if ($redis->lastsave() === $lastLastSave) {
                $reply->data = "Couldn't save your selection";
            }
            $reply->status = 200;
            $reply->data = 'Meal Saved Successfully...';
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

header("Content-type: application/json");

// If the reply data is empty, unset the variable
if($reply->data === null) {
	unset($reply->data);
}

echo json_encode($reply);