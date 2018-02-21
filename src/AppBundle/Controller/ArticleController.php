<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Article;
use AppBundle\Form\ArticleType;

/**
 * Controller to add/edit/delete Articles
 */
class ArticleController extends Controller
{
	/**
	 * Lists all articles available in database
	 *
     * @Route("/article/list", name="article_list")
     */
    public function listAction(Request $request)
    {	
    	$em = $this->getDoctrine()->getManager();
    	$repo = $em->getRepository('AppBundle:Article');
    	$articleList = $repo->findAll();

        // replace this example code with whatever you need
        return $this->render('article/list.html.twig', [
            'articleList' => $articleList,
        ]);
    }

	/**
	 * Creates a new article using a form.
	 *
     * @Route("/article/create", name="article_create")
     */
    public function createAction(Request $request)
    {
    	$article = new Article();
    	$form = $this->createForm(ArticleType::class, $article);

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$article = $form->getData();

    		$em = $this->getDoctrine()->getManager();
    		$em->persist($article);
    		$em->flush();

    		$request->getSession()->getFlashBag()->add('success', 'Article successfully created!');
    		return $this->redirectToRoute('article_view', [ 'id' => $article->getId() ]);
    	}

    	return $this->render('article/create.html.twig', [
    		'form' => $form->createView(),
    	]);
    }

    /**
     * Allows users to view an article
     *
     * @Route("/article/view/{id}", name="article_view")
     */
    public function viewAction(Request $request, Article $article)
    {
    	return $this->render('article/view.html.twig', [ 'article' => $article ]);
    }

    /**
     * Allows users to edit an existing article
     * 
     * @Route("/article/edit/{id}", name="article_edit")
     */
    public function editAction(Request $request, Article $article) 
    {
    	$form = $this->createForm(ArticleType::class, $article);

    	$form->handleRequest($request);

    	if ($form->isSubmitted() && $form->isValid()) {
    		$article = $form->getData();

    		$em = $this->getDoctrine()->getManager();
    		$em->flush();

    		$request->getSession()->getFlashBag()->add('success', 'Article successfully edited!');
    		return $this->redirectToRoute('article_view', [ 'id' => $article->getId() ]);
    	}

    	return $this->render('article/edit.html.twig', [
    		'form' => $form->createView(),
    		'article' => $article,
    	]);
    }

    /**
     * Allows users to delete an article
     *
     * @Route("/article/delete/{id}", name="article_delete")
     */
    public function deleteAction(Request $request, Article $article)
    {
    	$em = $this->getDoctrine()->getManager();
    	$em->remove($article);
		$em->flush();

		$request->getSession()->getFlashBag()->add('success', 'Article successfully deleted!');
		return $this->redirectToRoute('article_create');
    }
}