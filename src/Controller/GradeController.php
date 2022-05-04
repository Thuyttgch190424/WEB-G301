<?php

namespace App\Controller;

use App\Entity\Grade;
use App\Form\GradeType;
use App\Repository\GradeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


#[Route('/grade')]
/**
 * @IsGranted("ROLE_ADMIN")
 */
class GradeController extends AbstractController
{
    #[Route('/', name: 'grade_index')]
    public function grade_index(ManagerRegistry $managerRegistry)
    {
        $grades = $managerRegistry->getRepository(Grade::class)->findAll();

        if (!$grades) {
            throw $this->createNotFoundException(
                'No grades found in the database.'
            );
        }
        return $this->render('grade/index.html.twig', [
            'grades' => $grades
        ]);
    }

    #[Route('/add', name: 'add_grade')]
    public function add_grade(Request $request, ManagerRegistry $managerRegistry)
    {
        $grade = new Grade;
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $manager = $managerRegistry->getManager();
            $manager->persist($grade);
            $manager->flush();
            $this->addFlash("Success", "Grade added successfully !");
            return $this->redirectToRoute('grade_index');
        }
        return $this->renderForm('grade/add.html.twig', [
            'gradeForm' => $form
        ]);
    }


    #[Route('/detail/{id}', name: 'grade_detail')]
    public function grade_detail(ManagerRegistry $managerRegistry, $id)
    {
        $grade = $managerRegistry->getRepository(Grade::class)->find($id);

        if ($grade == null) {
            $this->addFlash('Error', "Grade not found !");
            return $this->redirectToRoute("grade_index");
        }

        return $this->render('grade/detail.html.twig', [
            'grade' => $grade,
            
        ]);
    }

    #[Route('/delete/{id}', name: 'delete_grade')]
    public function grade_Delete($id, ManagerRegistry $managerRegistry)
    {
        $grade = $managerRegistry->getRepository(Grade::class)->find($id);
        if (!$grade){
            $this->addFlash("Error", "Grade not found !");
            return $this->redirectToRoute("grade_index");
        }
        $manager = $managerRegistry->getManager();
        $manager->remove($grade);
        $manager->flush();
        $this->addFlash("Success", "Grade deleted successfully !");
        return $this->redirectToRoute("grade_index");
        
}

    #[Route('/edit/{id}', name: 'edit_grade')]
    public function gradeEdit($id, Request $request, ManagerRegistry $managerRegistry)
    {
        $grade = $managerRegistry->getRepository(Grade::class)->find($id);
        if (!$grade){
            $this->addFlash("Error", "Grade not found !");
            return $this->redirectToRoute("grade_index");
        }
        $form = $this->createForm(GradeType::class, $grade);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($grade);
            $manager->flush();
            $this->addFlash("Success", "Grade edited successfully !");
            return $this->redirectToRoute("grade_index");
        }
        return $this->renderForm(
            'grade/edit.html.twig',
            [
                'gradeForm' => $form
            ]
        );
    }

    #[Route('/asc', name: 'grade_asc')]
   public function sortAsc(GradeRepository $gradeRepository, ManagerRegistry $registry) {
       $grades = $gradeRepository->sortGradeAsc();
       return $this->render("grade/index.html.twig",
                            [
                                'grades' => $grades,
                            ]);
   }

   #[Route('/desc', name: 'grade_desc')]
   public function sortDesc(GradeRepository $gradeRepository, ManagerRegistry $registry) {
      
       $grades = $gradeRepository->sortGradeDesc();
       return $this->render("grade/index.html.twig",
                            [
                                'grades' => $grades
                            ]);
   }
}
