<?php

namespace App\Form;

use App\Entity\Student;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class StudentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class,
        [
            'label' => 'Student Name',
            'required' => true,
            'attr' => [
                'minlength' => 5,
                'maxlength' => 50,
            ]
        ])
        ->add('email', TextType::class,
        [
            'label' => 'Student Email',
            'required' => true
        ])
        ->add('studentId', TextType::class,
        [
            'label' => 'Student ID',
            'required' => true
        ])
        ->add('birthday', DateType::class, 
        [
            'label' => 'Student Birthday',
            'required' => true,
            'widget' => 'single_text'
        ])
        ->add('phone', TextType::class,
        [
            'label' => 'Student Phone',
            'required' => true
        ])
        
        ->add('image', TextType::class,
        [
            'label' => 'Student Image',
            'required' => true
            ])
        ->add('save', SubmitType::class,
        [
            'label' => 'Save'
        ])
        // ->add('courses')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Student::class,
        ]);
    }
}
