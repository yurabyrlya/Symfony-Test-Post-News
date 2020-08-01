<?php

namespace App\Controller;

use App\Form\NewsType;
use PhpParser\Node\Stmt\Return_;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\News;

class NewsController extends AbstractController
{

    /**
     * @Route("/", name="show")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Request $request)
    {
        $method =  $request->getMethod();
        $newsRep = $this->getDoctrine()->getRepository(News::class);
        $news = $newsRep->findBy([], ['id'=> 'DESC']);
        $newsRep->setPreviewPost($news);

        return $this->render('news/show.html.twig', [
            'title' => 'This page will show all posts news',
            'method' => $method,
            'news' =>$news,
        ]);
    }

    /**
     * @Route("/addnews", name="addnews")
     *
     */
    public function addNewsAction(Request $request)
    {
        $rep = $this->getDoctrine()->getRepository(News::class);
        $postNum = $rep->findOneBy([], ['id'=> 'DESC'])->getId();

        $news = new News();
        $news->setCreatedAt(new \DateTime());

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

               $em = $this->getDoctrine()->getManager();
               $news = $form->getData();
               $news->setTitle($news->getTitle() . " " . $postNum );
               $em->persist($news);
               $em->flush();

               return $this->redirectToRoute('show');
        }

        return $this->render('news/addPost.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/showPost/{post}", name="showPost")
     * @param Request $request
     * @param News $post
     * @return string
     */
    public function showPostNewsAction(Request $request , News $post)
    {
        return $this->render('news/showPost.html.twig',[
            'post' => $post,
        ]);

    }

    /**
     * @Route("/delete/{post}", name="deletePost")
     * @param News $post
     * @return string
     */
    public function deletePostAction(News $post)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();

        } catch (\Exception $e) {}

        return $this->redirectToRoute('show');

    }

    /**
     * @Route("/update/{post}", name="updatePost")
     * @param Request $request
     * @param News $post
     * @return string
     */
    public function updatePostAction( Request $request ,  News $post )
    {
        $form = $this->createForm(NewsType::class, $post , ['attr' => ['data' =>'update'] ]);
        $form->handleRequest($request);
        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($form->getData());
                $em->flush();
                return $this->redirectToRoute('show');
            }

        } catch (\Exception $e) {

        }

      return  $this->render('news/updatePost.html.twig', [ 'form' => $form->createView() ]);

    }

}
