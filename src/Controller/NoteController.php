<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NoteRepository;
use Symfony\Component\Serializer\SerializerInterface;

class NoteController extends AbstractController
{
    #[Route('/api/notes', name: 'app_note', methods: ['GET'])]
    public function getNoteList(NoteRepository $noteRepository, SerializerInterface $serializer): JsonResponse
    {
        $noteList = $noteRepository->findAll();
        $jsonNoteList = $serializer->serialize($noteList, 'json');
        return new JsonResponse($jsonNoteList, Response::HTTP_OK, [], true);
    }
}
