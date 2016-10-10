<?php

namespace Tests;

use Exception;
use InvalidArgumentException;
use PasswordGenerator\Characters\GenericCharacter;
use PasswordGenerator\Generator;
use PasswordGenerator\Random\RandomInterface;

class GeneratorTest extends TestCase
{
    public function testLowerUpperNumber()
    {
        $generator = new Generator;
        $generator->requireLowercase()
            ->requireUppercase()
            ->requireNumber()
            ->length(8);

        $this->assertEquals(8, strlen($generator->get()));

        for ($i = 0; $i < 50; $i++) {
            // Test 50 passwords to match the requirements
            $this->assertRegExp('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}/', $generator->get());
        }
    }

    public function testGenerator()
    {
        $generator = new Generator;
        $generator->requireLowercase()
            ->requireUppercase()
            ->requireNumber()
            ->requireSymbol(3)
            ->length(8);

        $this->assertEquals(8, strlen($generator->get()));

        $lastPassword = '';

        for ($i = 0; $i < 50; $i++) {
            // Test 50 passwords to match the requirements
            $password = $generator->get();
            // This password should not equal the last one
            $this->assertNotEquals($password, $lastPassword);
            $lastPassword = $password;
            // lowercase, uppercase, number and symbol
            $this->assertRegExp('/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*\(\)_\-=\+\/]).{8,}/', $password);
            // three or more symbols
            $this->assertRegExp('/(?:.*[!@#\$%\^&\*\(\)_\-=\+\/]){3}/', $password);
        }
    }

    public function testCustomCharacterType()
    {
        $customCharacterType = new class extends GenericCharacter
        {
            public function getCharacterSet() : string
            {
                return 'z';
            }
        };

        $generator = new Generator;
        $generator->addCharacterType('test', $customCharacterType);
        $generator->requireTest(4)->length(4);

        $this->assertEquals('zzzz', $generator->get());
    }

    public function testCustomRandomGenerator()
    {
        $customRandomGenerator = new class implements RandomInterface
        {
            public function get(int $min, int $max) : int
            {
                return $min;
            }
        };

        $generator = new Generator;
        $generator->setRandomNumberGenerator($customRandomGenerator);

        // This "random" generator will always return the minimum value, so the password will always be the same
        $this->assertEquals('A!aaaaaa', $generator->requireUppercase()->requireSymbol()->get());
    }

    /**
     * @expectedException Exception
     * @expectedExceptionMessage Fulfilling the minimum requirements exceeds desired password length.
     */
    public function testInvalidRequirements()
    {
        $generator = new Generator;
        $generator->requireLowercase(2)
            ->requireUppercase(2)
            ->requireNumber(2)
            ->requireSymbol(2)
            ->length(7);

        $this->assertEquals(7, $generator->getLength());

        $generator->get();
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Requirement using identifier "notexisting" not found.
     */
    public function testInvalidRequirementIdentifier()
    {
        $generator = new Generator;
        $generator->requireNotExisting();
    }
}
