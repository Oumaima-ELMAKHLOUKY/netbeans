<?php

namespace App\Tests;

use App\Entity\Article;
use DateTime;
use PHPUnit\Framework\TestCase;

class ArticleUnitTest extends TestCase
{
    //si les champs sont valide
    public function testValid()
    {
        $date = new DateTime('08-03-2000');
        $article = new Article;

        $article
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
        $article = new Article;

        $article
            ->setText("text")
            ->setDate($date);

        $this->assertFalse($article->getText() === "falsetext");
        $this->assertFalse($article->getDate() === $dateFalse);

    }

    //si les champs sont vide
    public function testVide()
    {
        $article = new Article();

        $this->assertEmpty($article->getText());
        $this->assertEmpty($article->getDate());
    }
}
