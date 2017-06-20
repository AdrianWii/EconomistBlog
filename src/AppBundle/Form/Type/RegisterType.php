<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType {
    
    public function getName(){
        return 'register_form';
    }
    
        public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
                ->add('name', TextType::class, array(
                    'label'=>'Imię'
                    ))
                ->add('email', EmailType::class, array(
                    'label'=>'E-mail'
                    ))
                ->add('sex', ChoiceType::class, array(
                    'choices' => array(
                        ' meżczyzna' => 'm', 
                        ' kobieta' => 'k'),
                    'expanded' => true, 'label'=>'Płeć'))
                ->add('birthday', BirthdayType::class, array(
                    'label'=>'Data urodzin', 
                    'placeholder'=>'--', 
                    'empty_data'=>NULL
                    ))
                ->add('country', CountryType::class, array(
                    'label'=>'Kraj', 
                    'placeholder'=>'--', 
                    'empty_data'=>NULL
                    ))
                ->add('course', ChoiceType::class, array(
                    'label'=>'Wybierz kurs',
                    'choices' => array(
                        'AutoCAD'=>'ac',
                        '3DsMax'=>'am',
                        'Revit'=>'ar'
                        ),
                    'placeholder'=>'--', 
                    'empty_data'=>NULL
                    ))
                ->add('invest', ChoiceType::class, array(
                    'choices' => array(
                        'akcje'=>'a',
                        'obligacje'=>'o'),
                    'expanded' => true,
                    'multiple' => true
                    ))
                ->add('comments', TextareaType::class)
                ->add('paymentfile', FileType::class, array(
                    'label' => 'Potwierdzenie zapłaty'
                    ))
                ->add('rules', CheckboxType::class, array(
                    'mapped' => false,
                    'constraints'=>array(
                        new Assert\NotBlank()
                        )
                    ))
                ->add('save', SubmitType::class);
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Register',
            ]);
    }
}