<?php

namespace AdminBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AdminBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Response;
use AdminBundle\Form\Type\CategoryType;
use AdminBundle\Entity\Article;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * Class ArticleController
 * @package AdminBundle\Controller
 * @Route("/admin")
 */
class ArticleController extends Controller
{

    /**
     * @Route("/category/form",name="category_form_update")
     */
    public function formAction(Request $request)
    {
        $cate = new Category();
        $cate->setName('aa');
        $cate->setStatus(1);

        /*$form = $this->createFormBuilder($cate)
            ->add('name',TextType::class,
                array(
                   'max_length'=>4,
                    'label'  => '名称',
                    )
            )
            ->add('status',IntegerType::class)
            ->add('saved',SubmitType::class)
            ->getForm();*/
        $form = $this->createForm(CategoryType::class,$cate);



        $form->handleRequest($request);


        if($form->isSubmitted() && $form->isValid()){

            $cate = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($cate);
            $em->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('AdminBundle:article:dd.html.twig',array('form'=>$form->createView()));
    }
    /**
     * @Route("/category/index",name="category_index")
     */
    public function categoryAction()
    {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:Category');
        //$category = $repository->findAll();

        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.id','ASC')
            //->setMaxResults(1)
            ->getQuery();



        $category = $query->getResult();

        return $this->render("AdminBundle:Article:category.html.twig",array('res'=>$category));
    }
    /**
     * @Route("/category/add",name="category_add")
     */
    public function addcateAction()
    {
        return $this->render('AdminBundle:Article:category_add.html.twig');
    }
    /**
     * @Route("/category/save",name="category_add_save")
     * @Method("POST")
     */
    public function savecateAction(Request $request)
    {
        //$repository = $this->getDoctrine()->getRepository('AdminBundle:Category'); //查找用
        $em = $this->getDoctrine()->getManager();
        $category = new Category();

        $name = $request->request->get('name');
        $status = $request->request->get('status');

        $category->setName($name);
        $category->setStatus($status);

        $em->persist($category);
        $em->flush();


        return $this->redirectToRoute('category_index');
    }
    /**
     * @Route("/category/{id}/edit",requirements={"id":"\d+"},name="category_edit")
     */
    public function editcateAction($id,Request $request)
    {
        $repository = $this->getDoctrine()->getRepository('AdminBundle:Category');
        $category = $repository->findOneById($id);

        $form = $this->createForm(CategoryType::class,$category);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $category = $form->getData();
            $em = $this->getDoctrine()->getManager();
           // $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('AdminBundle:article:category_edit.html.twig',array('form'=>$form->createView()));

    }

    /**
     * @Route("/category/{id}/delete",requirements={"id":"\d+"},name="category_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AdminBundle:Category')->find($id);
        $em->remove($category);
        $em->flush();
       // return new JsonResponse(array('msg'=>'删除成功','status'=>200));
        return $this->redirectToRoute('category_index');
    }
    /**
     * @Route("/article/index",name="article_index",requirements={"id":"\d+"})
     */
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page',1);

       // \Doctrine\Common\Util\Debug::dump($category);
        $repository = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Article');
       // $query = $repository->createQueryBuilder('p')->getQuery();
       // $result = $query->getResult();
       // return $this->render('AdminBundle:Article:index.html.twig',array('res'=>$result));
        $query = $repository->createQueryBuilder('p');
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate($query, $page,10);
        return $this->render('AdminBundle:Article:index.html.twig',array('pagination' => $pagination));
    }
    /**
     * @Route("/article/add",name="article_add")
     */
    public function article_addAction(Request $request)
    {
       // $repository = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Article');
        $em = $this->getDoctrine()->getManager();

        $article = new Article();
        $category = $this->getCategory(); //目前尚未解决如何在articleType里获取category,所以创建表单转移到控制器里
        $tag = $this->getTag();
        //$form = $this->createForm(ArticleType::class);
        $form = $this->createFormBuilder($article)
           /* ->add('categoryId',ChoiceType::class,array(
                'choices'    =>  $category,
                'choices_as_values'  =>  true, //前端显示的是 启用和禁用 否则 显示的 1和0
                'label'  =>  '文章分类',
                // 'expanded'   =>  true,  //为真 如果设置为true，单选按钮或复选框将呈现（取决于multiple值）。如果为false，则会呈现select元素。
                 //'data'=>1, //默认值
                //'multiple'=>true, //true是checkbox
            ))*/
           ->add('categoryId', EntityType::class, [
               'class' => 'AdminBundle:Category',
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('c');
               },
               'choice_label' => 'name',
               'choices_as_values' => true,
               'label'  =>  '文章分类',
           ])
           /* ->add('tagId',ChoiceType::class,array(
                'choices'    =>  $tag,
                'choices_as_values'  =>  true, //前端显示的是 启用和禁用 否则 显示的 1和0
                'label'  =>  '标签',
                 'expanded'   =>  true,  //为真 如果设置为true，单选按钮或复选框将呈现（取决于multiple值）。如果为false，则会呈现select元素。
                 'data'=>array(), //默认值
                'multiple'=>true, //true是checkbox
            ))*/
           ->add('tagId', EntityType::class, [
               'class' => 'AdminBundle:Tag',
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('c');
               },
               'choice_label' => 'name',
               'choices_as_values' => true,
               'label'  =>  '标签',
           ])

            ->add('title',TextType::class,array('label'=>'标题'))
            ->add('descript',TextareaType::class,array('label'=>'描述'))
            ->add('views',IntegerType::class,array('label'=>'阅读量','data'=>0)) //设置默认值
            ->add('createdAt',TextType::class,array('label'=>'时间','data'=>date('Y-m-d H:i:s'))) //设置默认值
            ->add('status',ChoiceType::class,array(
                'label'=>'状态',
                'choices'=>array('显示'=>1,'不显示'=>0),
                'choices_as_values'=>true,
                'expanded'=>true,
                'multiple'=>false,
                'data'=>1
            )) //设置默认值
            ->add('status_reply',ChoiceType::class,array(
                'label'=>'允许回复',
                'choices'=>array('允许'=>1,'不允许'=>0),
                'choices_as_values'=>true,
               // 'expanded'=>false,
            ))
            ->add('position',ChoiceType::class,array(
                'label'=>'显示位置',
                'choices'=>array('内页'=>1,'首页'=>2,'底部'=>3),
                'choices_as_values'=>true,
                'expanded'=>false,
                )) //设置默认值
            ->add('content',TextareaType::class,array('label'=>'内容','attr'=>array('editor'=>'editor')))
            ->add('save',SubmitType::class,array('label'=>'保存'))
            ->getForm();
        ;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isvalid()){
            $article = $form->getData();
           // $tagid = $article->getTagId();
            $createAt = $article->getCreatedAt();
            $createAt = $createAt ? $createAt : time();
            //$article->setTagId(implode(',',$tagid));
            $article->setCreatedAt(strtotime($createAt));
            $article->setUpdatedAt(strtotime($createAt));

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_index');
        }

        return $this->render('AdminBundle:Article:article_add.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @Route("/article/{id}/edit",name="article_edit",requirements={"id":"\d+"})
     *
     */
    public function article_edit($id,Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Article');
        $article = $repository->findOneById($id);
        if(!$article){
            throw $this->createNotFoundException('没有找到相关内容');
        }
        $form = $this->createFormBuilder($article)
            /* ->add('categoryId',ChoiceType::class,array(
                 'choices'    =>  $category,
                 'choices_as_values'  =>  true, //前端显示的是 启用和禁用 否则 显示的 1和0
                 'label'  =>  '文章分类',
                 // 'expanded'   =>  true,  //为真 如果设置为true，单选按钮或复选框将呈现（取决于multiple值）。如果为false，则会呈现select元素。
                  //'data'=>1, //默认值
                 //'multiple'=>true, //true是checkbox
             ))*/
            ->add('categoryId', EntityType::class, [
                'class' => 'AdminBundle:Category',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c');
                },
                'choice_label' => 'name',
                'choices_as_values' => true,
                'label'  =>  '文章分类',
                'data' => $article->getCategoryId(),
            ])
            /* ->add('tagId',ChoiceType::class,array(
                 'choices'    =>  $tag,
                 'choices_as_values'  =>  true, //前端显示的是 启用和禁用 否则 显示的 1和0
                 'label'  =>  '标签',
                  'expanded'   =>  true,  //为真 如果设置为true，单选按钮或复选框将呈现（取决于multiple值）。如果为false，则会呈现select元素。
                  'data'=>array(), //默认值
                 'multiple'=>true, //true是checkbox
             ))*/
            ->add('tagId', EntityType::class, [
                'class' => 'AdminBundle:Tag',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c');
                },
                'choice_label' => 'name',
                'choices_as_values' => true,
                'label'  =>  '标签',
                'data' => $article->getTagId(),
            ])

            ->add('title',TextType::class,array('label'=>'标题'))
            ->add('descript',TextareaType::class,array('label'=>'描述'))
            ->add('views',IntegerType::class,array('label'=>'阅读量','data'=>0)) //设置默认值
            ->add('createdAt',TextType::class,array('label'=>'时间','data'=>date('Y-m-d H:i:s',$article->getUpdatedAt()))) //设置默认值
            ->add('status',ChoiceType::class,array(
                'label'=>'状态',
                'choices'=>array('显示'=>1,'不显示'=>0),
                'choices_as_values'=>true,
                'expanded'=>true,
                'multiple'=>false,
                'data'=>1
            )) //设置默认值
            ->add('status_reply',ChoiceType::class,array(
                'label'=>'允许回复',
                'choices'=>array('允许'=>1,'不允许'=>0),
                'choices_as_values'=>true,
                // 'expanded'=>false,
            ))
            ->add('position',ChoiceType::class,array(
                'label'=>'显示位置',
                'choices'=>array('内页'=>1,'首页'=>2,'底部'=>3),
                'choices_as_values'=>true,
                'expanded'=>false,
            )) //设置默认值
            ->add('content',TextareaType::class,array('label'=>'内容','attr'=>array('editor'=>'editor')))
            ->add('save',SubmitType::class,array('label'=>'保存'))
            ->getForm();
        ;

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isvalid()){
            $article = $form->getData();
            // $tagid = $article->getTagId();
            $updatedAt = time();
            //$article->setTagId(implode(',',$tagid));
            $article->setUpdatedAt($updatedAt);
           // $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_index');
        }
        return $this->render('AdminBundle:Article:article_edit.html.twig',array('form'=>$form->createView()));
    }

    /**
     * @return mixed
     * @Route("/article/{id}/delete",name="article_delete",requirements={"id":"\d+"})
     */
    public function article_delete($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Article');
        $article = $repository->findOneById($id);
        if(!$article){
            throw $this->createNotFoundException('没有找到相关内容');
        }
        $em->remove($article);
        $em->flush();
        return $this->redirectToRoute('article_index');
    }

    public function getCategory()
    {
        $repository = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Category');
        $query = $repository->createQueryBuilder('p')
            ->where('p.status=1')->getQuery();
        $result =$query->getArrayResult();
        foreach($result as $v){
            $category[$v['name']] = $v['id'];
        }
        return $category;
    }
    public function getTag()
    {
        $tags = array();
        $repository = $this->getDoctrine()->getManager()->getRepository('AdminBundle:Tag');
        $query = $repository->createQueryBuilder('p')
            ->where('p.status=1')->getQuery();
        $result =$query->getArrayResult();
        foreach($result as $v){
            $tags[$v['name']] = $v['id'];
        }
        return $tags;
    }
}
