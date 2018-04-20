<?php
namespace  AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
       $builder
           ->add('name',TextType::class,array(
               'label'  =>  '名称',
           ))
           ->add('status',ChoiceType::class,array(
               'choices' => array(
                   '启用'    => '1',
                   '禁用'    => '0',
                ),
               'choices_as_values'   =>  true,
               'data'   =>  1 ,
               'label'  =>  '状态',
           ))
           ->add('submit',SubmitType::class);
    }
}