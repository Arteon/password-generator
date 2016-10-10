<?php

namespace PasswordGenerator\Characters;
use PasswordGenerator\Random\RandomInterface;

/**
 * Contract for character classes. Use this to create custom character ranges or requirements.
 *
 * @package password_generator
 * @author Karim Geiger <karim.geiger@heg.com>
 * @copyright 2016 Host Europe Group Ltd.
 */
abstract class GenericCharacter
{
    /** @var int */
    protected $min = 0;

    /**
     * Set minimum requirement for this character type.
     *
     * @param int $min
     */
    public function setMin(int $min)
    {
        $this->min = $min;
    }

    /**
     * Get minimum requirement for this character type.
     *
     * @return int
     */
    public function getMin() : int
    {
        return $this->min;
    }

    /**
     * Check if character type is required.
     *
     * @return bool
     */
    public function isRequired() : bool
    {
        return $this->min > 0;
    }

    /**
     * Get all possible characters for this type.
     *
     * @return string
     */
    abstract public function getCharacterSet() : string;

    /**
     * Get one random character for this type.
     *
     * @param RandomInterface $random
     * @return string
     */
    public function getRandomCharacter(RandomInterface $random) : string
    {
        $array = str_split($this->getCharacterSet());

        return $array[$random->get(0, count($array) - 1)];
    }
}
