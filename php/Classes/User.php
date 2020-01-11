<?php

namespace FroogalMeals\User;

require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
use DateTime;
use Exception;
use PharIo\Manifest\Email;
use PharIo\Manifest\InvalidUrlException;
use TypeError;
use Predis\Client;
use SplFixedArray;

/**
 * This is a FroogalMeals User class.  This class defines a user along with all of the user's info.
 */
class User {
    // Define State Variables
    
    /**
     * @var $userId
     */
    protected $userId;

    /**
     * @var $userRealName
     */
    protected $userRealName;

    /**
     * @var $userName
     */
    protected $userName;

    /**
     * @var $userEmail
     */
    protected $userEmail;

    /**
     * @var $userZipCode
     */
    protected $userZipCode;

    /**
     * @var $userBirthdate
     */
    protected $userBirthdate;

    /**
     * @var $userHeight
     */
    protected $userHeight;

    /**
     * @var $userWeight
     */
    protected $userWeight;

    /**
     * Constructor builds a user PHP object
     *
     * @param $userId
     * @param $userName
     * @param $userBirthdate
     * @param $userHeight
     * @param $userWeight
     */
    public function __construct(uuid $userId, string $userRealName, string $userName, string $userEmail, int $userZipCode, DateTime $userBirthdate, float $userHeight, int $userWeight) {
        $this->setUserId($userId);
        $this->setUserRealName($userRealName);
        $this->setUserName($userName);
        $this->setUserEmail($userEmail);
        $this->setUserZipCode($userZipCode);
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
    public function getUserId(): string {
        return $this->userId;
    }

    /**
     * SET the $userId state variable.
     * 
     * @param $userId
     */
    public function setUserId(uuid $userId): void {
        // Check if $userId is empty
        if(empty($userId)) {
            throw new TypeError("User Id must can;t be empty...");
        }

        // Set $userId
        $this->userId = $userId;
    }

    /**
     * GET $userRealName
     * 
     */
    public function getUserRealName(): string {
        return strval($this->userRealName);
    }

    /**
     * SET $userRealName
     */
    public function setUserRealName(string $userRealName): void {
        // Check if $userRealName is empty
        if(empty($userRealName)) {
            throw new InvalidUrlException("User's real name cannot be empty...");
        }
    }

    /**
     * GET User Name
     * 
     * @return $userName
     */
    public function getUserName(): string {
        return strval($this->userName);
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
     * GET $userEmail
     */
    public function getUserEmail() {
        return $this->userEmail;
    }

    /**
     * SET $userEmail
     */
    public function setUserEmail(string $userEmail) {
        try {
            $varifiedUserEmail = filter_var($userEmail, FILTER_SANITIZE_EMAIL);
        } catch(Exception $exception) {
            throw new Exception();
        }
    }

    /**
     * 
     */
    public function getUserZipCode(): int {
        return $this->userZipCode;
    }

    /**
     * 
     */
    public function setUserZipCode($userZipCode): void {
        $this->userZipCode = $userZipCode;
    }

    /**
     * @return $userBirthdate
     */
    public function getuserBirthdate(): DateTime {
        return new DateTime($this->userBirthdate);
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
        return floatval($this->userHeight);
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
        return intval($this->userWeight);
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

    /**
     * This method inserts a serialized data object into Redis.
     * 
     * @param $redis
     * @param $user
     */
    public function insert(Client $redis, User $user): void {
        // Set the redis value by userId
        $redis->set($user->getUserId(), serialize($user));
    }

    // GET FOO BY BARS //

    /**
     * getUserByUserId retrieves a user by the user's UUID and returns it.
     * 
     * @param $redis
     * @param $userId
     */
    public function getUserByUserId(Client $redis, string $userId): User {
        // Get the User by User's ID, will return a serialized object.
        $user = $redis->get($userId);
        $userObj = unserialize($user);

        // Return the UNSERIALIZED object
        return $userObj;
    }

    public function getAllUsers(Client $redis): SplFixedArray {
        
    }
}