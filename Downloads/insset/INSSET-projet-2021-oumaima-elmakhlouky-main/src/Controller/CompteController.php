<?php


namespace App\Controller;


use App\Entity\Article;
use App\Entity\Commente;
use App\Form\ArticleType;
use App\Form\CommenteType;
use Exception;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompteController extends AbstractController
{
    public function index()
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['user' => $user], ['date_publication' => 'desc']);

        if (!empty($articles)) {
            $commentaires = [];
            foreach ($articles as $article) {
                array_push($commentaires, $this->getDoctrine()
                    ->getRepository(Commente::class)
                    ->findBy(['article' => $article], ['date' => 'desc']));
            }
        } else {
            $articles = null;
        }
        if (empty($commentaires)) {
            $commentaires = null;
        }
        return $this->render('article/mon_compte.html.twig', [
            'articles' => $articles,
            'commentaires' => $commentaires,
        ]);
    }


    public function showArticle($id, Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $com = new Commente();
        $com->setUser($user);
        $com->setDate(new \DateTime('now'));

        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['id' => $id]);

        if (!empty($article)) {
            $commentaires = $this->getDoctrine()
                ->getRepository(Commente::class)
                ->findBy(['article' => $article], ['date' => 'desc']);
            $com->setArticle($article);

            $form = $this->createForm(CommenteType::class, $com);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $task = $form->getData();

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($task);
                $entityManager->flush();

                return $this->redirectToRoute('index');
            }

            return $this->render('article/showArticle.html.twig', [
                'article' => $article,
                'commentaires' => $commentaires,
                'form' => $form->createView(),
            ]);
        }else{
            $response = new Response(
                'L\'article spécifié n\'a pas été trouvée !',
                Response::HTTP_OK,
                ['content-type' => 'text/html']
            );
            $response->headers->set('Refresh', '5; url=/index'); //Redirect after 5 sec
            return $response;
        }
    }
}