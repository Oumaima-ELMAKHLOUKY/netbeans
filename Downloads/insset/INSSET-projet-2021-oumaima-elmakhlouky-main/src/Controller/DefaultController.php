<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Commente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    public function index()
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['publier' => '1'],['id' => 'desc']);

        if(!empty($articles)) {
            $commentaires = $this->getDoctrine()
                ->getRepository(Commente::class)
                ->findBy(['article' => $articles[0]]);
        }
        else{
            $articles[0] = null;
            $commentaires = null;
        }
        return $this->render('homepage.html.twig',[
            'article' => $articles[0],
            'commentaires' => $commentaires,
        ]);
    }
}