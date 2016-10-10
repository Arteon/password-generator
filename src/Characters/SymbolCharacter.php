<?php

namespace PasswordGenerator\Characters;

/**
 * Possible symbols for password generation.
 *
 * @package password_generator
 * @author Karim Geiger <karim.geiger@heg.com>
 * @copyright 2016 Host Europe Group Ltd.
 */
class SymbolCharacter extends GenericCharacter
{
    /**
     * Get all possible characters for this type.
     *
     * @return string
     */
    public function getCharacterSet() : string
    {
        return '!@#$%^&*()_-=+/';
    }
}
