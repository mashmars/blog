<?php

namespace HomeBundle\Controller;

use AdminBundle\Entity\Post;
use AdminBundle\Entity\User;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use HomeBundle\Controller\BaseController;
use HomeBundle\Form\Type\UserType;

class DefaultController extends BaseController
{
    /**
     * @Route("/{cate}",name="home")
     */
    public function indexAction($cate='' ,Request $request)
    {
        $page = $request->query->get('page',1);

        //文章
        $list = 10;
        $em = $this->getDoctrine()->getManager();
        $article_repository = $em->getRepository('AdminBundle:Article');

        $query = $article_repository->createQueryBuilder('p');

        //动态查询
        if($cate){
            $param = explode('-' , $cate);
            if(!$param[0]){
                throw $this->createNotFoundException('没有找到相关信息');
            }
            if($param[1] == 1){
                //分类
                $id = $em->getRepository('AdminBundle:Category')->findOneByName($param[0]);
                if(!$id){
                    throw $this->createNotFoundException('没有找到相关信息');
                }
                $query->andWhere("p.categoryId = :categoryId")->setParameter('categoryId',$id);
            }elseif($param[1] == 2){
                //标签
                $id = $em->getRepository('AdminBundle:Tag')->findOneByName($param[0]);
                if(!$id){
                    throw $this->createNotFoundException('没有找到相关信息');
                }
                $query->andWhere("p.tagId = :tagId")->setParameter('tagId',$id);
            }else{
                throw $this->createNotFoundException('没有找到相关信息');
            }
        }

        $paginator = $this->get('knp_paginator');
        $paginates = $paginator->paginate($query,$page,$list);

        //获取所有分类
        $category = $this->getCategory();
        //获取所有标签
        $tag = $this->getTag();
        //获取热门文章
        $pop_article = $this->getPopArticle();

        return $this->render('HomeBundle:Index:index.html.twig',array(
            'paginator'=>$paginates,
            'category'=>$category,
            'tag'=>$tag,
            'pop_article'=>$pop_article,
            'current'=>$cate,//当前标签
        ));
    }

    /**
     * @Route("/blog/{id}",requirements={"id":"\d+"},name="blog_detail")
     */
    public function show($id,Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Article');
        $article = $repository->findOneById($id);
        if(!$article){
            throw $this->createNotFoundException('没有找到相关内容');
        }

        //增加阅读量 设置cookie
        $cookie = $request->cookies->get('article'.$id);
        if(!$cookie){
            $response = new Response();
            $response->headers->setCookie(new Cookie('article'.$id,$id,time()+5*3600));
            $response->send();
            //阅读+1
            $views = $article->getViews();
            $article->setViews(++$views);
            $em->flush();
        }


        //上一篇
        $query1 = $repository->createQueryBuilder('p')
            ->where("p.id < :id")
            ->setParameter('id',$id)
            ->setMaxResults(1)
            ->getQuery();
        $prev = $query1->getArrayResult();
        //下一篇
        $query2 = $repository->createQueryBuilder('p')
            ->where("p.id > :id")
            ->setParameter('id',$id)
            ->setMaxResults(1)
            ->getQuery();
        $next = $query2->getArrayResult();

        //获取所有分类
        $category = $this->getCategory();
        //获取所有标签
        $tag = $this->getTag();
        //获取热门文章
        $pop_article = $this->getPopArticle();

        //获取当前文章对应的标签
        $tag_id = $article->getTagId();
        $current = $em->getRepository('AdminBundle:Tag')->findOneById($tag_id);
        //获取当前评论
        $query = $em->getRepository('AdminBundle:Post')->createQueryBuilder('p')
            ->where("p.article = :id")
            ->setParameter('id',$id)
            ->orderBy('p.id','DESC')
            ;

       // $posts = $query->getResult();
        $page = $request->query->get('page',1);
        $paginator = $this->get('knp_paginator');
        $paginate = $paginator->paginate($query,$page,5);


        /*form*/
        $user = new User();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isValid() && $form->isSubmitted()){
            $user = $form->getData();
            $username = $user->getUsername();
            $email = $user->getEmail();
            $website = $user->getWebsite();
            $exists = $em->getRepository('AdminBundle:User')->findOneBy(array('username'=>$username,'email'=>$email,'website'=>$website));

            if($exists){
                 $content = $user->getContent();
                //插入评论
                $post = new Post();
                $post->setArticle($article);
                $post->setUserid($exists);
                $post->setContent($content);
                $post->setStatus(1);
                $post->setCreatedAt(time());
                $em->persist($post);
                $em->flush();
                return $this->redirectToRoute("blog_detail",array('id'=>$id));
             }else{
                $updatedAt = time();
                $user->setCreatedAt($updatedAt);
                $user->setStatus(1);
                $em->persist($user);
                $em->flush();

                $username = $user->getUsername();
                $email = $user->getEmail();
                $website = $user->getWebsite();
                $exists = $em->getRepository('AdminBundle:User')->findOneBy(array('username'=>$username,'email'=>$email,'website'=>$website));

                $content = $exists->getContent();
                //插入评论
                $post = new Post();
                $post->setArticle($article);
                $post->setUserid($exists);
                $post->setContent($content);
                $post->setStatus(1);
                $post->setCreatedAt(time());
                $em->persist($post);
                $em->flush();
                return $this->redirectToRoute("blog_detail",array('id'=>$id));
             }

        }

        return $this->render('HomeBundle:Index:show.html.twig',array(
            'article'=>$article,
            'prev'=>$prev,
            'next'=>$next,
            'category'=>$category,
            'tag'=>$tag,
            'pop_article'=>$pop_article,
            'id'=>$id,
            'current'=>$current->getName() . '-' . '2', //当前标签
            'form'=>$form->createView(),
            'paginate'=>$paginate,
        ));
    }

}
