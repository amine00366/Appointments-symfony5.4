<?php

namespace App\Form;

use App\Entity\Appointment;
use App\Entity\Doctor;
use App\Entity\Typeappoinment;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraint;

class DateRange extends Constraint
{
    public $message = 'Les dates de réservation se chevauchent avec une réservation existante.';
}
class AppointmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('appointmentDate', DateTimeType::class, [
                'label' => 'Choisissez votre date de début ',
                'required' => true ,
                'data' => new \DateTime(),
                'widget' => 'single_text',
                
                'constraints' => [
                    new GreaterThanOrEqual([
                        'value' => 'today',
                        'message' => 'La date et l\'heure doivent être supérieures ou égales à la date actuelle.',
                    ]),
                ],
                'attr' => [
                    'min' => (new \DateTime())->format('Y-m-d\TH:i'),
                    'class' => 'form-control datetimepicker-input',
                ],
               
            ])
            ->add('categorie', ChoiceType::class, [
                'placeholder' => '',
                'choices'  => [
                    'en ligne' => 'en ligne',
                    'présentielle' => 'présentielle',
                    
                ],
                'attr' => [
                    'class' => 'form-control',
                    'data-toggle' => 'dropdown',
                     ],
                
            ])
           /* ->add('datefin', DateTimeType::class, [
                'data' => new \DateTime(),
                'label' => 'Choisissez votre date de fin',
                'required' => true ,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control datetimepicker-input',
                ],
                
            ])*/
            ->add('type', EntityType::class, [
                'placeholder' => '',
                'class' => Typeappoinment::class,
                'choice_label' => 'nomtype',
                'label' => 'Choisissez le type de rendez vous',
                'required' => true ,
                'attr' => [
                    'class' => 'form-control',
                    'data-toggle' => 'dropdown',
                     ],
                
            ])
            
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Appointment::class,
        ]);
    }
}
