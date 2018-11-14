<?php
/**
 * Created by PhpStorm.
 * User: d15000320
 * Date: 24/01/2018
 * Time: 14:55
 */

namespace AppBundle\Form;


use AppBundle\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReviewForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
                'attr' => [
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Commentaire',
                ],
                'required' => true,
            ])
            ->add('isPositive', ChoiceType::class, [
                'choices' => [
                    'J\'aime' => true,
                    'Je n\'aime pas' => false
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'mdl-radio__button',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }
}