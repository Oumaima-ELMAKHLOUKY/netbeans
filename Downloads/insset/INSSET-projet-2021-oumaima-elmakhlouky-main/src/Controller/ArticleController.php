<?php


namespace App\Controller;

use App\Entity\Article;
use App\Entity\Commente;
use App\Entity\User;
use App\Form\ArticleType;
use App\Form\publishType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataMapper\CheckboxListMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends AbstractController
{
    public function index(){
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findBy(['publier' => '1'], ['date_publication' => 'desc']);

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
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'commentaires' => $commentaires,
        ]);
    }
    public function monCompte()
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

    public function new(Request $request)
    {
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $article = new Article();
        $article->setUser($user);
        $article->setDatePublication(new \DateTime('now'));
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $imgName = $form["imageADDR"]->getData();
            if (!empty($imgName) && $imgName != "") {
                $imgName = explode("/", $imgName)[4];
                if (in_array($imgName, $this->getImages(), true)) {
                    $article->setImageADDR($imgName); //Getting only the image filename instead of every file
                } else {
                    $response = new Response(
                        'L\'image spécifié n\'a pas été trouvée !',
                        Response::HTTP_OK,
                        ['content-type' => 'text/html']
                    );
                    $response->headers->set('Refresh', '5; url=/article/new'); //Redirect after 5 sec
                    return $response;
                }
            }

            $task = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('article');

        }
        return $this->render('article/new.html.twig', [
            'form' => $form->createView(),
            'images' => $this->getImages(),
        ]);
    }

    public function add_image_page(Request $request)
    {
        return $this->render('article/add_image.html.twig');
    }

    public function add_image_action(Request $request)
    {
        $output = array('uploaded' => false);
        $file = $request->files->get('file');
        $fileName = $file->getClientOriginalName();

        // set your uploads directory
        $uploadDir = '../public/articleImages/';
        if (!file_exists($uploadDir) && !is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }
        if ($file->move($uploadDir, $fileName)) {
            $output['uploaded'] = true;
            $output['fileName'] = $fileName;
        }
        return new JsonResponse($output);
    }

    public function showMyArticles(Request $request){
        $user = $this->container->get('security.token_storage')->getToken()->getUser();
        $form = $this->createFormBuilder($user)
            ->add('articles', CollectionType::class,[
                'entry_type' => publishType::class,
                'entry_options' => ['label' => false],
            ])

            ->add('save', SubmitType::class,[
                'attr' => ['class' => 'save btn btn-secondary'],
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('article');
        }
        return $this->render('article/showmy.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return array
     */
    public function getImages(): array
    {
        $directory = "../public/articleImages/";
        return preg_grep('~\.(jpeg|jpg|png)$~', scandir($directory));
    }
}