<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TeacherType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, 
            [
                'label' => 'Teacher Name',
                'required' => true,
                'attr' => [
                    'minlength' => 5,
                    'maxlength' => 50
                ]
            ])
            ->add('birthday', DateType::class, 
            [
                'label' =>  'Birthday',
                'required' => true,
                'widget' => 'single_text'
            ])
            ->add('email', TextType::class, 
            [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('image', TextType::class, 
            [
                'label' => 'Image',
                'required' => true,
            ])
            ->add('phone', TextType::class, 
            [
                'label' => 'Phone',
                'required' => true,
                'attr' => [
                    'minlength' => 9,
                    'maxlength' => 12
                ]
            ])
            ->add('course', EntityType::class, [
                'label' => 'Course Name',
                'required' => true,
                'class' => Course::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' =>true
            ])
            ->add('save', SubmitType::class,
            [
                'label' => 'Save'
            ]);
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Teacher::class,
        ]);
    }
}
