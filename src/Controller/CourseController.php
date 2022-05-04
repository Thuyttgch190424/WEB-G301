<?php

namespace App\Controller;

use App\Entity\Course;
use App\Form\CourseType;
use App\Repository\CourseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/course')]
/**
 * @IsGranted("ROLE_ADMIN")
 */
class CourseController extends AbstractController
{
    #[Route('/', name: 'course_index')]
    public function course_index(ManagerRegistry $managerRegistry)
    {
        $courses = $managerRegistry->getRepository(Course::class)->findAll();
        if (!$courses) {
            throw $this->createNotFoundException(
                'No courses found in the database.'
            );
        }
        return $this->render('course/index.html.twig', [
            'courses' => $courses,
        ]);
    }
    #[Route('/add', name: 'add_course')]
    public function add_course(Request $request, ManagerRegistry $managerRegistry, CourseRepository $courseRepository)
    {
        $course = new Course;
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!($courseRepository->checkName($form["name"]->getData()))) {
                $manager = $managerRegistry->getManager();
                $manager->persist($course);
                $manager->flush();
                $this->addFlash("Success", "Course added successfully !");
                return $this->redirectToRoute('course_index');
            } else {
                $this->addFlash("Error", "Duplicate course name !");
                return $this->redirectToRoute('add_course');
            }
        }
        return $this->renderForm('course/add.html.twig', [
            'courseForm' => $form
        ]);
    }

    #[Route('/detail/{id}', name: 'course_detail')]
    public function course_detail(ManagerRegistry $managerRegistry, $id)
    {
        $course = $managerRegistry->getRepository(Course::class)->find($id);

        if ($course == null) {
            $this->addFlash('Error', "Course not found !");
            return $this->redirectToRoute("course_index");
        }

        return $this->render('course/detail.html.twig', [
            'course' => $course,

        ]);
    }

    #[Route('/delete/{id}', name: 'delete_course')]
    public function course_Delete($id, ManagerRegistry $managerRegistry)
    {
        $course = $managerRegistry->getRepository(Course::class)->find($id);
        if (!$course) {
            $this->addFlash("Error", "Course not found !");
            return $this->redirectToRoute("Course_index");
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($course);
        $manager->flush();
        $this->addFlash("Success", "Course deleted successfully !");
        return $this->redirectToRoute("course_index");
    }
    #[Route('/edit/{id}', name: 'edit_course')]
    public function course_Edit($id, Request $request, ManagerRegistry $managerRegistry)
    {
        $course = $managerRegistry->getRepository(Course::class)->find($id);
        if (!$course) {
            $this->addFlash("Error", "Course not found !");
            return $this->redirectToRoute("course_index");
        }
        $form = $this->createForm(CourseType::class, $course);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($course);
            $manager->flush();
            $this->addFlash("Success", "Course edited successfully !");
            return $this->redirectToRoute("course_index");
        }
        return $this->renderForm(
            'course/edit.html.twig',
            [
                'courseForm' => $form
            ]
        );
    }

    #[Route('/search', name: 'course_search')]
    public function search(Request $request, CourseRepository $courseRepository, ManagerRegistry $registry)
    {
        $keyword = $request->get('name');
        $courses = $courseRepository->search($keyword);
        return $this->render(
            "course/index.html.twig",
            [
                'courses' => $courses,
            ]
        );
    }
}
