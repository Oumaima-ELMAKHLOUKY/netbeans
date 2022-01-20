<?php

namespace App\Tests;

use App\Entity\Commente;
use DateTime;
use PHPUnit\Framework\TestCase;

class CommenteUnitTest extends TestCase
{
    //si les champs sont valide
    public function testValid()
    {
        $date = new DateTime('08-03-2000');
        $commente = new Commente;

        $commente
            ->setText("text")
            ->setDate($date);


        $this->assertTrue($tache->getText() === "text");
        $this->assertTrue($tache->getDate() === $date);

    }

    //si les champs sont non valide
    public function testInValid()
    {
        $date = new DateTime('08-03-2000');
        $dateFalse = new DateTime('07-01-2000');
        $commente = new Commente;

        $commente
            ->setText("text")
            ->setDate($date);

        $this->assertFalse($commente->getText() === "falsetext");
        $this->assertFalse($commente->getDate() === $dateFalse);

    }

    //si les champs sont vide
    public function testVide()
    {
        $commente = new Commente();

        $this->assertEmpty($commente->getText());
        $this->assertEmpty($commente->getDate());
    }
}