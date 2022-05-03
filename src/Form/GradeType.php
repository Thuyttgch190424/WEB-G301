<?php

namespace App\Form;

use App\Entity\Grade;
use App\Entity\Course;
use App\Entity\Student;
use App\Entity\Teacher;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GradeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('student', EntityType::class,
        [
            'label' => 'Student ID',
            'required' => true,
            'class' => Student::class,
            'choice_label' => 'studentId',
            'multiple' => false,  
            'expanded' => false
        ])
        ->add('course', EntityType::class,
        [
            'label' => 'Course Name',
            'required' => true,
            'class' => Course::class,
            'choice_label' => 'name',
            'multiple' => false,  
            'expanded' => false
        ])
            ->add('grade', TextType::class,
            [
                'label' => 'Grade',
                'required' => true
            ])
            ->add('comment', TextType::class,
            [
                'label' => 'Comment',
                'required' => true
            ])
            ->add('teacher', EntityType::class,
            [
                'label' => 'Teacher Name',
                'required' => true,
                'class' => Teacher::class,
                'choice_label' => 'name',
                'multiple' => false,  
                'expanded' => false
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
            'data_class' => Grade::class,
        ]);
    }
}
