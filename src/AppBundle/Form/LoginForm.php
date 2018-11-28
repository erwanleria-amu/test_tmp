<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, array(
                'attr' => array(
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Nom d\'utilisateur',
                    'autofocus' => 'autofocus'
                )
            ))
            ->add('_password', PasswordType::class, array(
                'attr'=> array(
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Mot de passe'
                )
            ));
    }
}