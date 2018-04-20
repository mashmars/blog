<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    //获取所有分类
    public function getCategory()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Category');
        $article = $em->getRepository('AdminBundle:Article');
        $query = $repository->createQueryBuilder('p')->getQuery();
        $category = $query->getArrayResult();
        foreach ($category as &$v){
            $v['title'] =  $v['name']. '-' . '1' ;
            $articles = $article->createQueryBuilder('a')->where('a.categoryId=:id')->setParameter('id',$v['id'])->getQuery()->getArrayResult();
            $v['count'] = count($articles);
        }
        return $category;
    }

    //获取所有标签
    public function getTag()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Tag');
        $query = $repository->createQueryBuilder('p')->getQuery();
        $tag = $query->getArrayResult();
        foreach ($tag as &$v){
            $v['title'] =  $v['name']. '-' . '2' ;
        }
        return $tag;
    }

    //文章热门文章
    public function getPopArticle()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AdminBundle:Article');
        $query = $repository->createQueryBuilder('p')
            ->orderBy('p.views','DESC')
            ->addOrderBy('p.id','DESC')
            ->setMaxResults(5)
            ->getQuery();
        $result = $query->getArrayResult();
        return $result;
    }
}
