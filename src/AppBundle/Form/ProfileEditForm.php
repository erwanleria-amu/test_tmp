<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileEditForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description', TextAreaType::class, [
                'attr' => [
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Description',
                ],
                'required' => false
            ])
            ->add('avatar', FileType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'uploadBtn'
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }
}