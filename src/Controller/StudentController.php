<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/student')]
class StudentController extends AbstractController
{
    #[Route('/', name: 'student_index')]
    public function student_index(ManagerRegistry $managerRegistry)
    {
        $students = $managerRegistry->getRepository(Student::class)->findAll();
        $courses = $managerRegistry->getRepository(Course::class)->findAll();
        if (!$students) {
            throw $this->createNotFoundException(
                'No students found in the database.'
            );
        }
        return $this->render('student/index.html.twig', [
            'students' => $students,
            'courses' => $courses
        ]);
    }

    #[Route('/add', name: 'add_student')]
    public function add_student(Request $request, ManagerRegistry $managerRegistry)
    {
        $student = new Student;
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash("Success", "Student added successfully !");
            return $this->redirectToRoute('student_index');
        }
        return $this->renderForm('student/add.html.twig', [
            'studentForm' => $form
        ]);
    }


    #[Route('/detail/{id}', name: 'student_detail')]
    public function student_detail(ManagerRegistry $managerRegistry, $id)
    {
        $student = $managerRegistry->getRepository(Student::class)->find($id);

        if ($student == null) {
            $this->addFlash('Error', "Student not found !");
            return $this->redirectToRoute("student_index");
        }

        return $this->render('student/detail.html.twig', [
            'student' => $student,

        ]);
    }

    #[Route('/delete/{id}', name: 'delete_student')]
    public function student_Delete($id, ManagerRegistry $managerRegistry)
    {
        $student = $managerRegistry->getRepository(Student::class)->find($id);
        if (!$student) {
            $this->addFlash("Error", "Student not found !");
            return $this->redirectToRoute("student_index");
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($student);
        $manager->flush();
        $this->addFlash("Success", "Student deleted successfully !");
        return $this->redirectToRoute("student_index");
    }

    #[Route('/edit/{id}', name: 'edit_student')]
    public function studentEdit($id, Request $request, ManagerRegistry $managerRegistry)
    {
        $student = $managerRegistry->getRepository(Student::class)->find($id);
        if (!$student) {
            $this->addFlash("Error", "Student not found !");
            return $this->redirectToRoute("student_index");
        }
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($student);
            $manager->flush();
            $this->addFlash("Success", "Student edited successfully !");
            return $this->redirectToRoute("student_index");
        }
        return $this->renderForm(
            'student/edit.html.twig',
            [
                'studentForm' => $form
            ]
        );
    }

   #[Route('/search', name: 'student_search')]
   public function search (Request $request, StudentRepository $studentRepository, ManagerRegistry $registry) {
       $keyword = $request->get('name');
       $students = $studentRepository->search($keyword);
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students,
                            ]);
   }

   #[Route('/asc', name: 'student_asc')]
   public function sortAsc(StudentRepository $studentRepository, ManagerRegistry $registry) {
       $students = $studentRepository->sortStudentAsc();
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students,
                            ]);
   }

   #[Route('/desc', name: 'student_desc')]
   public function sortDesc(StudentRepository $studentRepository, ManagerRegistry $registry) {
      
       $students = $studentRepository->sortStudentDesc();
       return $this->render("student/index.html.twig",
                            [
                                'students' => $students
                            ]);
   }

   #[Route('/filter/{id}', name: 'course_filter')]
   public function filter ($id, ManagerRegistry $registry) {
       $courses = $registry->getRepository(Course::class)->findAll();
       $course = $registry->getRepository(Course::class)->find($id);
       $students = $course->getStudents();
       return $this->render("student/index.html.twig",
                            [
                                    'students' => $students,
                                    'courses' => $courses
                            ]);
   }
}
