<?php
require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/Classes/User.php");

use FroogalMeals\User\User;
use Ramsey\Uuid\Uuid;
use Predis\Client;

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    // Get request method
    $method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

    // Create a Redis Instance for
    $redis = new Client();
    $redis->connect('127.0.0.1');

    // Check the connection by pinging the server
    try {
        $redis->ping();
    } catch (Predis\Connection\ConnectionException $exception) {
        $exceptionType = get_class($exception);
        throw new $exceptionType($exception->getMessage());
    }

    // Do things based on the Request Method
    if ($method === 'GET') {
        try {
            // Filter and sanitize inputs
            $userId = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $userName = filter_input(INPUT_GET, 'userName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            // Create an object to insert into the DB
            $thing = new User(Uuid::uuid4(), "Mark", new DateTime(), 4.9, 309);

            print($thing->getUserByUserId($redis, 'b03d00a5-f800-4991-bec2-f89b5992379d'));
        } catch (Exception $exception) {
            throw new Exception("Something went wrong while filtering inputs...");
        }
        $reply->data = unserialize($redis->get("Mark"));
    } else if ($method === 'POST') {
        echo "POST";
    }



    // Serialize the object
    $thingString = serialize($thing);

    // Insert Serialized object
    $redis->set($thing->getUserName(), $thingString);
} catch (Predis\Response\ServerException $exception) {
    throw new Exception("Couldn't connect to Redis...");
}

/*
header("Content-type: application/json");

// If the reply data is empty, unset the variable
if($reply->data === null) {
	unset($reply->data);
}

echo json_encode($reply);
*/
