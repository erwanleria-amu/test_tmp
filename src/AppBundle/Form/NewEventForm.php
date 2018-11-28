<?php
namespace AppBundle\Form;


use AppBundle\Entity\Itinerary;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewEventForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Titre',
                ],
                'required' => true,
            ])
            ->add('tripDate', DateType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Date de départ',
                'attr' => [
                    'class' => 'mdl-textfield__input'
                ]
            ])
            ->add('tripTime', TimeType::class, [
                'widget' => 'single_text',
                'required' => true,
                'label' => 'Heure de départ',
                'attr' => [
                    'class' => 'mdl-textfield__input'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Description',
                ],
                'required' => true,
            ])
            ->add('nbParticipants', TextType::class, [
                'attr' => [
                    'class' => 'mdl-textfield__input',
                    'placeholder' => 'Nombre de participants (vous inclus)'
                ],
                'required' => true,
            ])
            ->add('itinerary', EntityType::class, [
                'class' => Itinerary::class,
                'choice_label' => 'name',
                'choices' => $options['data']['itineraries'],
                'attr' => [
                    'class' => 'mdl-textfield__input',
                    'id' => 'event-latitude'
                ],
                'required' => true,

            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }
}