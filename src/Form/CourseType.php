<?php

namespace App\Form;

use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, 
            [
                'label' => 'Course Name',
                'required' => true,
                'attr' => [
                    'minlength' => 2,
                    'maxlength' => 50
                ]
            ])
            ->add('description', TextType::class, 
            [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'minlength' => 10,
                    'maxlength' => 500
                ]
            ])
            ->add('image',TextType::class, [
                'label' => 'Course Image',
                'required' => true,
            ])
            ->add('teachers', EntityType::class, [
                'label' => 'Teacher Name',
                'required' => true,
                'class' => Teacher::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' =>true
            ])
            ->add('students', EntityType::class, [
                'label' => 'Student ID',
                'required' => true,
                'class' => Student::class,
                'choice_label' => 'studentId',
                'multiple' => true,
                'expanded' => true
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
            'data_class' => Course::class,
        ]);
    }
}
