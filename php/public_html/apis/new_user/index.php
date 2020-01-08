<?php

require_once(dirname(__DIR__, 3) . "/vendor/autoload.php");
require_once(dirname(__DIR__, 3) . "/Classes/User.php");

use FroogalMeals\User\User;
use Ramsey\Uuid\Uuid;
use Predis\Client;

$thing = new User(Uuid::uuid4(), "Mark Waid", new DateTime(), 4.9, 309);

var_dump($thing);

// Create empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
    // Create a Redis Instance for
} catch() {

}
