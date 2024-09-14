<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NoteRepository;
use App\Entity\Note;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class NoteController extends AbstractController
{
    #[Route('/api/notes', name: 'note', methods: ['GET'])]
    public function getNoteList(NoteRepository $noteRepository, SerializerInterface $serializer): JsonResponse
    {
        $noteList = $noteRepository->findAll();
        $jsonNoteList = $serializer->serialize($noteList, 'json');
        return new JsonResponse($jsonNoteList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/notes/{id}', name: 'detailNote', methods: ['GET'])]
    public function getNoteDetail(Note $note, SerializerInterface $serializer): JsonResponse
    {
        $jsonNote = $serializer->serialize($note, 'json');
        return new JsonResponse($jsonNote, Response::HTTP_OK, [], true);
    }

    #[Route('/api/notes/{id}', name: 'deleteNote', methods: ['DELETE'])]
    public function deleteNote(Note $note, EntityManagerInterface $em): JsonResponse 
    {
        $em->remove($note);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('/api/notes', name:"createNote", methods: ['POST'])]
    public function createNote(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {
        $note = $serializer->deserialize($request->getContent(), Note::class, 'json');

        $em->persist($note);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_CREATED);
    }

    #[Route('/api/notes/{id}', name:"updateNote", methods:['PUT'])]
    public function updateNote(Request $request, SerializerInterface $serializer, Note $note, EntityManagerInterface $em): JsonResponse 
    {
        $updatedNote = $serializer->deserialize($request->getContent(), Note::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $note]);
        
        $em->persist($updatedNote);
        $em->flush();
        
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }
}
