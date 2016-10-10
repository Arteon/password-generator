<?php

namespace PasswordGenerator\Characters;

/**
 * All general numeric characters.
 *
 * @package password_generator
 * @author Karim Geiger <karim.geiger@heg.com>
 * @copyright 2016 Host Europe Group Ltd.
 */
class NumberCharacter extends GenericCharacter
{
    /**
     * Get all possible characters for this type.
     *
     * @return string
     */
    public function getCharacterSet() : string
    {
        return '0123456789';
    }
}
