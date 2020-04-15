<?php
    namespace App\Controller;

    use App\Entity\Article;

    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;

    class ArticleController extends AbstractController {
        /**
         * @Route("/", name="article_list", methods={"GET"})
         */
        public function index() {
            // return new Response(
            //     '<html><body>Hello</body></html>'
            // );
            
            // $articles = ['Article 1','Article 2'];

            $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

            return $this->render('articles/index.html.twig', array ('articles' => $articles));
        }

        /**
         * @Route("/article/new", name="new_article", methods={ "GET", "POST" })
         */
         public function new(Request $request) {
            $article = new Article();

            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array(
                    'required' => true,
                    'attr' => array('class' => 'form-control')
                ))
                ->add('body', TextareaType::class, array(
                    'required' => false,
                    'attr' => array('class' => 'form-control')
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Create',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    $article = $form->getData();
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($article);
                    $entityManager->flush();

                    return $this->redirectToRoute('article_list');
                }

                // Pass 'form' to view template with helper functions form_start(), form_end()
                return $this->render('articles/new.html.twig', array(
                    'form' => $form->createView()
                ));
         }

         /**
         * @Route("/article/edit/{id}", name="edit_article", methods={ "GET", "POST" })
         */

        public function edit(Request $request, $id) {
            $article = new Article();
            $article = $this->getDoctrine()->getRepository
            (Article::class)->find($id);

            $form = $this->createFormBuilder($article)
                ->add('title', TextType::class, array(
                    'required' => true,
                    'attr' => array('class' => 'form-control')
                ))
                ->add('body', TextareaType::class, array(
                    'required' => false,
                    'attr' => array('class' => 'form-control')
                ))
                ->add('save', SubmitType::class, array(
                    'label' => 'Update',
                    'attr' => array('class' => 'btn btn-primary mt-3')
                ))
                ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();

                    return $this->redirectToRoute('article_list');
                }

                // Pass 'form' to view template with helper functions form_start(), form_end()
                return $this->render('articles/edit.html.twig', array(
                    'form' => $form->createView()
                ));
         }

        /**
        * @Route("/article/{id}", name="article_show")
        */
        public function show($id) {
            $article = $this->getDoctrine()->getRepository
            (Article::class)->find($id);

            return $this->render('articles/show.html.twig', array
            ('article' => $article));
        }

        /**
        * @Route("/article/delete/{id}", name="article_delete", methods={"DELETE"})
        */
        public function delete(Request $request, $id) {
            // Use doctrine to find article id, then delete article id
            // Copied from show() {...}
            $article = $this->getDoctrine()->getRepository
            (Article::class)->find($id);

            // copied entity manager from new() {...}
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $response = new Response();
            $response->send();
        }
         
        // /**
        //  * @Route("/article/save")
        //  */
        // public function save() {
        //     $entityManager = $this->getDoctrine()->getManager();

        //     // use App\Entity\Article
        //     $article = new Article();
        //     $article->setTitle('Article Two');
        //     $article->setBody('This is the body for Article Two');

        //     $entityManager->persist($article);

        //     $entityManager->flush();

        //     return new Response('Saved an article with the id of '.$article->getId());
        // }
    }