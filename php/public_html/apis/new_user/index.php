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

    // Set DB to User DB which is R0
    $redis->select(0);

    // Check the connection by pinging the server
    try {
        $redis->ping();
    } catch (Predis\Connection\ConnectionException $exception) {
        $exceptionType = get_class($exception);
        throw new $exceptionType("Cannot connecto to a Redis instance...");
    }

    // Do things based on the Request Method
    if ($method === 'GET') {
        try {
            // Filter and sanitize inputs
            $userId = filter_input(INPUT_GET, 'userId', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $userName = filter_input(INPUT_GET, 'userName', FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

            $reply->data = $userId;
        } catch (Exception $exception) {
            throw new Exception("Something went wrong while filtering inputs...");
        }
    } else if ($method === 'POST') {
        try {
            // Generate a new user UUID
            $userId = Uuid::uuid4();

            // Filter and sanitize post inputs
            $userRealName = filter_input(INPUT_POST, $_POST['userRealName'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $userName = filter_input(INPUT_POST, $_POST['userName'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $userEmail = filter_input(INPUT_POST, $_POST['userEmail'], FILTER_SANITIZE_EMAIL, FILTER_VALIDATE_EMAIL);
            $userZipCode = filter_input(INPUT_POST, $_POST['userZipCode'], FILTER_VALIDATE_INT);
            $userBirthdate = filter_input(INPUT_POST, $_POST['userBirthdate'], FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
            $userHeight = filter_input(INPUT_POST, $_POST['userHeight'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_VALIDATE_FLOAT);
            $userWeight = filter_input(INPUT_POST, $_POST['userWeight'], FILTER_SANITIZE_NUMBER_INT, FILTER_VALIDATE_INT);
        } catch() {

        }
    }



    // Serialize the object
    //$thingString = serialize($thing);

    // Insert Serialized object
    //$redis->set($thing->getUserName(), $thingString);
} catch (Predis\Response\ServerException $exception) {
    throw new Exception("Couldn't connect to Redis...");
}


header("Content-type: application/json");

// If the reply data is empty, unset the variable
if ($reply->data === null) {
    unset($reply->data);
}

echo json_encode($reply);