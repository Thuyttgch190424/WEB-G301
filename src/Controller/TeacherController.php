<?php

namespace App\Controller;

use App\Entity\Teacher;
use App\Form\TeacherType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/teacher')]
class TeacherController extends AbstractController
{
    #[Route('/', name: 'teacher_index')]
    public function teacher_index(ManagerRegistry $managerRegistry)
    {
        $teachers = $managerRegistry->getRepository(Teacher::class)->findAll();
        if (!$teachers) {
            throw $this->createNotFoundException('No teachers found in the database!');
        }
        return $this->render('teacher/index.html.twig', [
            'teachers' =>$teachers
        ]);
    }
    #[Route('/add', name: 'add_teacher')]
    public function add_teacher(Request $request, ManagerRegistry $managerRegistry)
    {
        $teacher = new Teacher;
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($teacher);
            $manager->flush();
            $this->addFlash("Success", "Teacher added successfully!");
            return $this->redirectToRoute('teacher_index');
        }
        return $this->renderForm('teacher/add.html.twig', [
            'teacherForm' => $form
        ]);
    }
    #[Route('/detail/{id}', name:'teacher_detail')]
    public function teacher_detail(ManagerRegistry $managerRegistry, $id)
    {
        $teacher = $managerRegistry->getRepository(Teacher::class)->find($id);

        if ($teacher == null) {
            $this->addFlash('Error', "Teacher not found !");
            return $this->redirectToRoute("teacher_index");
        }

        return $this->render('teacher/detail.html.twig', [
            'teacher' => $teacher,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_teacher')]
    public function teacher_Delete($id, ManagerRegistry $managerRegistry)
    {
        $teacher = $managerRegistry->getRepository(Teacher::class)->find($id);
        if (!$teacher) {
            $this->addFlash("Error", "Teacher not found !");
            return $this->redirectToRoute("teacher_index");
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($teacher);
        $manager->flush();
        $this->addFlash("Success", "Teacher deleted successfully !");
        return $this->redirectToRoute("teacher_index");
    }
    #[Route('edit/{id}', name: 'edit_teacher')]
    public function teacher_Edit($id, Request $request, ManagerRegistry $managerRegistry)
    {
        $teacher = $managerRegistry->getRepository(teacher::class)->find($id);
        if(!$teacher){
            $this->addFlash("Error", "Teacher not found!");
            return $this->redirectToRoute("teacher_index");
        }
        $form = $this->createForm(TeacherType::class, $teacher);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $manager = $managerRegistry->getManager();
            $manager->persist($teacher);
            $manager->flush();
            $this->addFlash("Success", "Teacher edited successfully!");
            return $this->redirectToRoute("teacher_index");
        }
        return $this->renderForm(
            'teacher/edit.html.twig',[
                'teacherForm' => $form
            ]
            );

    }
}
