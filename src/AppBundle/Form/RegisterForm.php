<?php
namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegisterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('_username', TextType::class, array('attr' => array('class' => 'mdl-textfield__input', 'placeholder' => 'Nom d\'utilisateur')))
            ->add('_email', EmailType::class, array('attr' => array('class' => 'mdl-textfield__input', 'placeholder' => 'Email')))
            ->add('_plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'first_options'  => array('label' => 'Mot de passe', 'attr' => array('class' => 'mdl-textfield__input', 'placeholder' => 'Mot de passe')),
                'second_options' => array('label' => 'Confirmer le mot de passe', 'attr' => array('class' => 'mdl-textfield__input', 'placeholder' => 'Confirmer le mot de passe'))
            ))
            /*
            ->add('termsAccepted', CheckboxType::class, array('label' => 'J\'accepte', 'required' => false, 'mapped' => false, 'constraints' => new IsTrue()))
            */
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
        'data_class' => User::class,
        ));
    }
}