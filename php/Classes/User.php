<?php

namespace FroogalMeals\User;

require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
use DateTime;

class User {
    // Define State Variables
    
    /**
     * @var $userId
     */
    public $userId;

    /**
     * @var $userName
     */
    public $userName;

    /**
     * @var $userBirthdate
     */
    public $userBirthdate;

    /**
     * @var $userHeight
     */
    public $userHeight;

    /**
     * @var $userWeight
     */
    public $userWeight;

    /**
     * Constructor builds a user PHP object
     *
     * @param $userId
     * @param $userName
     * @param $userBirthdate
     * @param $userHeight
     * @param $userWeight
     */
    public function __construct(uuid $userId, string $userName, DateTime $userBirthdate, float $userHeight, int $userWeight) {
        $this->setUserId($userId);
        $this->setUserName($userName);
        $this->setuserBirthdate($userBirthdate);
        $this->setUserHeight($userHeight);
        $this->setUserWeight($userWeight);
    }

    // Define getters and setters

    /**
     * GET User Id
     * 
     * @return $userId
     */
    public function getUserId(): uuid {
        return $this->userId;
    }

    /**
     * SET User Id
     * 
     * @param $userId
     */
    public function setUserId(uuid $userId): void {
        // Check if $userId is empty
        if(empty($userId)) {
            throw new \TypeError("User Id must can;t be empty...");
        }

        $this->userId = $userId;
    }

    /**
     * GET User Name
     * 
     * @return $userName
     */
    public function getUserName(): string {
        return $this->userName;
    }

    /**
     * SET User Name
     * 
     * @param $userName
     */
    public function setUserName(string $userName): void {
        // Check if $userName is empty
        if(empty($userName)) {
            throw new \InvalidArgumentException("Username cannot be emtpy...");
        }

        $this->userName = $userName;
    }

    /**
     * @return $userBirthdate
     */
    public function getuserBirthdate(): DateTime {
        return $this->userBirthdate;
    }

    /**
     * @param $userBirthdate
     */
    public function setuserBirthdate(DateTime $userBirthdate): void {
        // Check if $userBirthdate is empty
        if(empty($userBirthdate)) {
            throw new \InvalidArgumentException("User Birthdate must not be empty.");
        }

        $this->userBirthdate = $userBirthdate;
    }

    /**
     * @return $userHeight
     */
    public function getUserHeight(): float {
        return $this->userHeight;
    }

    public function setUserHeight(float $userHeight): void {
        // Check if $userHeight is empty
        if(empty($userHeight)) {
            throw new \InvalidArgumentException("User Height can't be empty...");
        }

        $this->userHeight = $userHeight;
    }

    /**
     * @return $userWeight
     */
    public function getUserWeight(): int {
        return $this->userWeight;
    }

    /**
     * @param $userWeight
     */
    public function setUserWeight(int $userWeight): void {
        // Check if $userWeight is empty
        if(empty($userWeight)) {
            throw new \InvalidArgumentException("User weight can't be empty...");
        }
        
        $this->userWeight = $userWeight;
    }
}

$thing = new User(Uuid::uuid4(), "Mark Waid", new DateTime(), 5.9, 238);
var_dump($thing);