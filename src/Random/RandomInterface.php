<?php

namespace PasswordGenerator\Random;

/**
 * Interface for random number generators.
 *
 * @package password_generator
 * @author Karim Geiger <karim.geiger@heg.com>
 * @copyright 2016 Host Europe Group Ltd.
 */
interface RandomInterface
{
    /**
     * Get random number between (including) $min and $max.
     *
     * @param int $min
     * @param int $max
     * @return int
     */
    public function get(int $min, int $max) : int;
}
