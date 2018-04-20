<?php
namespace AdminBundle\Form\Type;

use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $category = array('d'=>'d');
       $builder
           ->add('categoryId',ChoiceType::class,array(
               'choices'    =>  $category,
               'choices_as_values'  =>  true, //前端显示的是 启用和禁用 否则 显示的 1和0
               'label'  =>  '文章分类',
               // 'expanded'   =>  true  //为真 如果设置为true，单选按钮或复选框将呈现（取决于multiple值）。如果为false，则会呈现select元素。
               // 'data'=>1, //默认值
               //'multiple'=>true, //true是checkbox
           ))
           ->add('tagId',TextType::class,array('label'=>'标签'))
           ->add('title',TextType::class,array('label'=>'标题'))
           ->add('save',SubmitType::class,array('label'=>'保存'))
       ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'AdminBundle\Entity\Article',
        ));
    }
}