<?php

namespace App\Controller;

use App\Entity\Board;
use App\Form\BoardType;
use App\Repository\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/board")
 */
class ApiBoardController extends AbstractController
{
    /**
     * @Route("/", name="_api_board_index", methods={"GET"})
     */
    public function index(BoardRepository $boardRepository): Response
    {

        return $this->json($boardRepository->findAll(), 200, [], ['groups' => 'board']);
    }


    /**
     * @Route("/add", name="_api_board_add", methods={"GET", "POST"})
     */
    public function addBoard(Request $request, BoardRepository $boardRepository, SerializerInterface $serializer, EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $jsonRecu = $request->getContent();

        try {
            $board = $serializer->deserialize($jsonRecu, Board::class, 'json');

            $errors = $validator->validate($board);

            if ($errors === 0) {
                return $this->json($errors, 400);
            };

            $em->persist($board);
            $em->flush();

            return $this->json($board, 201, [], ['goups' => 'board']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/new", name="app_api_board_new", methods={"GET", "POST"})
     */
    public function new(Request $request, BoardRepository $boardRepository): Response
    {
        $board = new Board();
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardRepository->add($board, true);

            return $this->redirectToRoute('app_api_board_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('api_board/new.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_api_board_show", methods={"GET"})
     */
    public function show(Board $board): Response
    {
        return $this->render('api_board/show.html.twig', [
            'board' => $board,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_api_board_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Board $board, BoardRepository $boardRepository): Response
    {
        $form = $this->createForm(BoardType::class, $board);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $boardRepository->add($board, true);

            return $this->redirectToRoute('app_api_board_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('api_board/edit.html.twig', [
            'board' => $board,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_api_board_delete", methods={"POST"})
     */
    public function delete(Request $request, Board $board, BoardRepository $boardRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $board->getId(), $request->request->get('_token'))) {
            $boardRepository->remove($board, true);
        }

        return $this->redirectToRoute('app_api_board_index', [], Response::HTTP_SEE_OTHER);
    }
}
