<?php

namespace App\Tests;

use App\Entity\User;
use DateTime;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{

    //si les champs sont valide
    public function testValid()
    {
        $user = new User();

        $date = new DateTime('06-01-2000');

        $user->setEmail("test@test.com")
            ->setPassword("password")
            ->setNom("nom")
            ->setPrenom("prenom")
            ->setDtn($date);


        $this->assertTrue($user->getEmail() === "test@test.com");
        $this->assertTrue($user->getPassword() === "password");
        $this->assertTrue($user->getNom() === "nom");
        $this->assertTrue($user->getPrenom() === "prenom");
        $this->assertTrue($user->getDtn() === $date);
    }

    //si les champs sont non valide
    public function testInValid()
    {
        $user = new User();

        $date = new DateTime('06-01-2000');
        $dateFalse = new DateTime('07-01-2000');

        $user->setEmail("test@test.com")
            ->setPassword("password")
            ->setNom("nom")
            ->setPrenom("prenom")
            ->setDtn($date);


        $this->assertFalse($user->getEmail() === "testFalse@test.com");
        $this->assertFalse($user->getPassword() === "Falsepassword");
        $this->assertFalse($user->getNom() === "Falsenom");
        $this->assertFalse($user->getPrenom() === "Falseprenom");
        $this->assertFalse($user->getDtn() === $dateFalse);
    }

    //si les champs sont vide
    public function testVide()
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getPrenom());
        $this->assertEmpty($user->getNom());
        $this->assertEmpty($user->getDtn());
    }
}
