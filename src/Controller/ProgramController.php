<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * Show all rows from Program’s entity
     *
     * @Route("/", name="index")
     */
    public function index(ProgramRepository $programRepository): Response
    {
        return $this->render(
            'program/index.html.twig',
            ['programs' => $programRepository->findAll()]
        );
    }

    /**
     * @Route("/show/{id<\d+>}/", methods={"GET"}, name="show")
     * Comment éviter id = 0?
     * Comment mettre par défaut 1 (?1)?
     * Comment forcer affichage URL lors de /show/ après avoir mis une valeur par défaut?
     */
    public function show(int $id, ProgramRepository $programRepository, SeasonRepository $seasonRepository): Response
    {
        $program = $programRepository->findOneById($id);
        //$program = $programRepository->findOneBy(['id' => $id]);

        $seasons = $seasonRepository->findBy([
            'program' => $program,
            ], ['number' => 'ASC']);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $id . ' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
        'program' => $program,
        'seasons' => $seasons,
        ]);
    }
}
