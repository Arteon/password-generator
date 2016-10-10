<?php

namespace PasswordGenerator;

use BadMethodCallException;
use Exception;
use InvalidArgumentException;
use PasswordGenerator\Characters;
use PasswordGenerator\Random;

/**
 * Password Generator.
 *
 * @package password_generator
 * @author Karim Geiger <karim.geiger@heg.com>
 * @copyright 2016 Host Europe Group Ltd.
 *
 * @method Generator requireUppercase(int $min = 1)
 * @method Generator requireLowercase(int $min = 1)
 * @method Generator requireNumber(int $min = 1)
 * @method Generator requireSymbol(int $min = 1)
 */
class Generator
{
    /** @var Characters\GenericCharacter[] All available character types. */
    protected $requirements = [];

    /** @var int Length of desired password. */
    protected $length = 8;

    /** @var Random\RandomInterface Random number generator. */
    protected $random = null;

    /**
     * Generator constructor. Initialize default character types.
     */
    public function __construct()
    {
        $this->random = new Random\RandomInt;

        $this->requirements = [
            'lowercase' => new Characters\LowercaseCharacter,
            'uppercase' => new Characters\UppercaseCharacter,
            'number' => new Characters\NumberCharacter,
            'symbol' => new Characters\SymbolCharacter
        ];
    }

    /**
     * Add your own custom character type using the given identifier. Require it using "requireYourIdentifier($min)".
     *
     * @param string $identifier
     * @param Characters\GenericCharacter $character
     * @return Generator
     */
    public function addCharacterType(string $identifier, Characters\GenericCharacter $character) : self
    {
        $this->requirements[strtolower($identifier)] = $character;

        return $this;
    }

    /**
     * Set alternative random number generator. Default is using random_int().
     *
     * @param Random\RandomInterface $random
     * @return Generator
     */
    public function setRandomNumberGenerator(Random\RandomInterface $random) : self
    {
        $this->random = $random;

        return $this;
    }

    /**
     * Set desired password length.
     *
     * @param int $length
     * @return Generator
     */
    public function length(int $length) : self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get defined password length.
     *
     * @return int
     */
    public function getLength() : int
    {
        return $this->length;
    }

    /**
     * Get new randomly generated password using given requirements and length.
     *
     * @return string
     * @throws Exception
     */
    public function get() : string
    {
        $password = $this->generateMinimumRequirements();

        if (count($password) > $this->length) {
            throw new Exception('Fulfilling the minimum requirements exceeds desired password length.');
        }

        $requirements = array_values($this->requirements);

        for ($i = count($password); $i < $this->length; $i++) {
            $requirement = $requirements[$this->random->get(0, count($requirements) - 1)];
            $password[] = $requirement->getRandomCharacter($this->random);
        }

        return implode('', $this->shuffleArray($password));
    }

    /**
     * Add new requirement. Call this method using requireRequirement($min);
     *
     * @param string $requirement
     * @param array $arguments
     * @return Generator
     */
    protected function addRequirement(string $requirement, array $arguments) : self
    {
        if (!isset($this->requirements[$requirement])) {
            throw new InvalidArgumentException('Requirement using identifier "' . $requirement . '" not found.');
        }

        $this->requirements[$requirement]->setMin((int)($arguments[0] ?? 1));

        return $this;
    }

    /**
     * Shuffle given array using the given random interface.
     *
     * @param array $array
     * @return array
     */
    private function shuffleArray(array $array)
    {
        $newArray = [];
        $length = count($array);

        for ($i = 0; $i < $length; $i++) {
            $array = array_values($array);
            $newArray[] = array_splice($array, $this->random->get(0, count($array) - 1), 1, [])[0];
        }

        return $newArray;
    }

    /**
     * Generate the minimum requirements. Length is not taken into account here.
     *
     * @return string[]
     */
    private function generateMinimumRequirements() : array
    {
        $array = [];

        foreach ($this->requirements as $requirement) {
            if ($requirement->isRequired()) {
                for ($i = 0; $i < $requirement->getMin(); $i++) {
                    $array[] = $requirement->getRandomCharacter($this->random);
                }
            }
        }

        return $array;
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return Generator
     * @throws BadMethodCallException
     */
    public function __call($name, $arguments = [])
    {
        if (strpos($name, 'require') === 0) {
            return $this->addRequirement(strtolower(substr($name, 7)), $arguments);
        }

        throw new BadMethodCallException();
    }
}
