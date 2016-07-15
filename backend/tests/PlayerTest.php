<?php
use PHPUnit\Framework\TestCase;
require '../lib/player.php';

class PlayerTest extends TestCase
{
    // ...

    public function testHasTag()
    {
        // Arrange
        $a = new Player("user");

        // Act
        $b = $a->HasTag(1);

        // Assert
        $this->assertEquals(true, $b);
    }

    // ...
}