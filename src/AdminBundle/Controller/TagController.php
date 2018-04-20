<?php
namespace  AdminBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Tag;
use AdminBundle\Form\Type\TagType;

/**
 * Class TagController
 * @package AdminBundle\Controller
 * @Route("/admin")
 */
class TagController extends Controller
{
    /**
     * @Route("/tag/index/{page}",requirements={"page":"\d+"},name="tag_index")
     */
    public function index($page=1)
    {
        $limit = 10;
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Tag');
        $query = $repository->createQueryBuilder('p');

        $paginate = $this->get('knp_paginator');
        $paginates = $paginate->paginate($query,$page,$limit);

        return $this->render('AdminBundle:Article:tag.html.twig',array('paginate'=>$paginates));
    }
    /**
     * @Route("/tag/add",name="tag_add")
     */
    public function add(Request $request)
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class,$tag);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $tag = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($tag);
            $em->flush();
            return $this->redirectToRoute('tag_index');
        }
        return $this->render('AdminBundle:Article:tag_add.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/tag/{id}/edit",requirements={"id":"\d+"},name="tag_edit")
     */
    public function edit($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AdminBundle:Tag')->findOneById($id);

        $form = $this->createForm(TagType::class,$tag);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $tag = $form->getData();
            $em->flush();
            return $this->redirectToRoute('tag_index');
        }
        return $this->render('AdminBundle:Article:tag_edit.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/tag/{id}/delete",requirements={"id":"\d+"},name="tag_delete")
     */
    public  function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AdminBundle:Tag')->findOneById($id);
        $em->remove($tag);
        $em->flush();

        return $this->redirectToRoute('tag_index');
    }
    /**
     * @Route("/tag/{id}/post",name="ajax_tag_delete")
     */
    public function ajax_delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('AdminBundle:Tag')->findOneById($id);
        $em->remove($tag);
        $em->flush();
        return new JsonResponse(array('info'=>'success','msg'=>'yes'),200);
    }

}