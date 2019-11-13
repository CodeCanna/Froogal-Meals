<?php

namespace CodeCanna\Meal;

require_once(dirname(__DIR__, 1) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
use DateTime;
use SplFixedArray;
use TypeError;
use Predis\Client;

/**
 * This is the Meal class.  This class represents a meal stored on Froogal-Meals
 * 
 * @author CodeCanna
 */
class Meal
{
    /**
     * @var $mealId
     */
    public $mealId;

    /**
     * @var $mealName
     */
    public $mealName;

    /**
     * @var $mealType ex. Breakfast, lunch, or dinner...or second breakfast?
     */
    public $mealType;

    /**
     * @var $mealDate
     */
    public $mealDate;

    /**
     * @var $mainIngredients
     */
    public $mainIngredients;

    /**
     * @var $calorieCount
     */
    public $calorieCount;

    /**
     * Constructor for the Meal object
     * @param $mealId
     * @param $mealName
     * @param $mealType
     * @param $calorieCount
     * @param $mealDate
     */
    public function __construct(string $mealId, string $mealName, string $mealType, DateTime $mealDate, string $mainIngredients, int $calorieCount)
    {
        // Set passed object values
        $this->setMealId($mealId);
        $this->setMealName($mealName);
        $this->setMealType($mealType);
        $this->setMealDate($mealDate);
        $this->setMainIngrds($mainIngredients);
        $this->setCalorieCount($calorieCount);
    }

    // ### Getters and Setters ### \\

    /** TODO
     * Gets the meal ID
     * @return $this->mealId
     */
    public function getMealId(): string
    {
        return $this->mealId;
    }

    /**
     * Sets the meal ID
     * @param $mealId
     */
    public function setMealId(string $mealId): void
    {
        if ($mealId === null) {
            throw new InvalidArgumentException("Meal ID is null?");
        }
        if (empty($mealId)) {
            throw new InvalidArgumentException("Meal ID cannot be empty...");
        }

        try {
            //$uuid = self::validateUuid($mealId);
        } catch (InvalidArgumentException $exception) {
            $exceptionType = get_class($exception);
            throw new $exceptionType($exception->getMessage());
        }

        $this->mealId = $mealId;
    }

    /**
     * Gets the name of the current set meal
     * @return string
     */
    public function getMealName(): string
    {
        return $this->mealName;
    }

    /**
     * @param $mealName
     */
    public function setMealName($mealName): void
    {
        // Check if $mealName is null
        if ($mealName === null) {
            throw (new InvalidArgumentException("Meal Name is null?"));
        }

        // Check if $mealName is empty
        if (empty($mealName)) {
            throw (new InvalidArgumentException("Meal name cannot be empty..."));
        }

        // Set $mealName
        $this->mealName = $mealName;
    }

    /**
     * Gets the set meal type
     *
     * @return string
     */
    public function getMealType(): string
    {
        return $this->mealType;
    }

    /**
     * Sets the meal type
     * @param $mealType
     */
    public function setMealType(string $mealType): void
    {
        // Check if $mealType is null
        if ($mealType === null) {
            throw new InvalidArgumentException("Meal Type is null?");
        }

        if (empty($mealType)) {
            throw new InvalidArgumentException("Meal Type cannot be empty...");
        }

        $this->mealType = $mealType;
    }

    /**
     * Get meal date
     * @return DateTime
     */
    public function getMealDate(): DateTime
    {
        return $this->mealDate;
    }

    /**
     * Sets the meal date
     * @param $mealDate
     */
    public function setMealDate(DateTime $mealDate): void
    {
        // Check if $mealDate is null
        if ($mealDate === null) {
            throw new InvalidArgumentException("Meal Date is null?");
        }

        // Check if $mealDate is empty
        if (empty($mealDate)) {
            throw new InvalidArgumentException("Meal date can't be empty...");
        }

        // Check if $mealDate is of type DateTime
        if (!$mealDate instanceof DateTime) {
            // Create custom error message.
            $typeGiven = gettype($mealDate);
            $errorMessage = "Meal date must be of type DateTime. " . strtoupper($typeGiven) . " given...";

            throw new TypeError($errorMessage);
        }

        // Set meal date
        $this->mealDate = $mealDate;
    }

    /**
     * Gets the main ingredients from the meal
     * @return string
     */
    public function getMainIngredients(): string
    {
        return $this->mainIngredients;
    }

    /**
     * @param $mainIngredients
     */
    public function setMainIngrds($mainIngredients): void
    {
        $this->mainIngredients = $mainIngredients;
    }

    /**
     * Gets the set calorie count for the meal
     * @return int
     */
    public function getCalorieCount(): int
    {
        return $this->calorieCount;
    }

    public function setCalorieCount($calorieCount): void
    {
        // Check if $calorieCount is null
        if ($calorieCount === null) {
            throw new InvalidArgumentException("Calorie Count is null?");
        }

        // Check if $calorieCount is empty
        if (empty($calorieCount)) {
            throw new InvalidArgumentException("Calorie count cannot be empty...");
        }

        // Check if $calorieCount is of type int
        if (gettype($calorieCount) !== 'integer') {
            // Create custom error message.
            $typeGiven = gettype($calorieCount);
            $errorMessage = "Meal date must be of type DateTime. " . strtoupper($typeGiven) . " given...";

            throw new TypeError($errorMessage);
        }

        // Set the calorie count
        $this->calorieCount = $calorieCount;
    }

    // ### Get Foo by Bars ### \\

    /**
     * @param $mealName
     * @param $redis a redis instance
     * @return CodeCanna\Meal
     */
    public function getMealByMealName(Client $redis, string $mealName): Meal
    {
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

        // Check if list exists in redis
        if(sizeof($redis->keys($mealName)) <= 0) {
            throw new \InvalidArgumentException("Meal not found...");
        }

        // If list exists get all of it's elements
        $mealData = $redis->lrange($mealName, 0, -1);

        // Convert date string to DateTime object
        $date = new DateTime($mealData[2]);
        $mealData[2] = $date;

        // Convert $calorieCount to int
        $convertedCalorieCount = intval($mealData[0]);
        $mealData[0] = $convertedCalorieCount;


        // Build meal from redis list
        $meal = new Meal($mealData[5], $mealData[4], $mealData[3], $mealData[2], $mealData[1], $mealData[0]);

        return $meal;
    }

    /**
     * @param $redis
     * @return SplFixedArray
     */
    public function getAllMeals(Client $redis): array {
        // Get all RedisDB list names
        $mealNames = $redis->keys('*');

        return $mealNames;
    }

    /**
	 * Returns an array of state variables formatted for JSON serialization.
	 *
	 * @return array
	 */
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		$fields["mealId"] = $this->mealId->toString();
		return($fields);
	}
}