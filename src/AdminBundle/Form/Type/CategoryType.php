<?php

namespace AdminBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,array $options)
    {
        $builder
            ->add('name',null,array('label'=>'名称'))
            ->add('status',ChoiceType::class, array(
                'choices'           => array(
                    '启用'    => '1',
                    '禁用'    => '0',
                ),
                'placeholder'       => false,
                'choices_as_values' => true,
                'label' => '状态',
               // 'data' => 0, //默认选中
                //'multiple'   =>  true ,
               // 'expanded'   =>  true  //为真 如果设置为true，单选按钮或复选框将呈现（取决于multiple值）。如果为false，则会呈现select元素。
            ))
            ->add('save',SubmitType::class)
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' =>'AdminBundle\Entity\Category',
        ));
    }


}
