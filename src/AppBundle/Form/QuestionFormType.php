<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 08.01.2018
 * Time: 2:56
 */

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('answer', CollectionType::class, array(
                // each entry in the array will be an "email" field
                'entry_type'   => TextType::class,
                // these options are passed to each "email" type
                'entry_options'  => array(
                    'attr'      => array('class' => 'email-box')
                )
                )
            )

            ->add('right', ChoiceType::class, [
                'choices' => [
                    'Yes' => true,
                    'No' => false,
                ]
            ])

        ;
    }

}