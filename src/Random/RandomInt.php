<?php

namespace PasswordGenerator\Random;

/**
 * Generate random numbers using random_int().
 *
 * @package password_generator
 * @author Karim Geiger <karim.geiger@heg.com>
 * @copyright 2016 Host Europe Group Ltd.
 */
class RandomInt implements RandomInterface
{
    public function get(int $min, int $max) : int
    {
        return random_int($min, $max);
    }
}
