<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    /**
     * Retrieves all Comments
     * @Rest\View()
     * @Rest\Get("/comments")
     */
    public function getComments(CommentRepository $commentRepository, SerializerService $serializer)
    {
        $comments=$commentRepository->findAll();
        $comments=$serializer->serializeData($comments);
        return $comments;
    }

    /**
     * Retrieves a Comment according to the id
     * @Rest\View()
     * @Rest\Get("/comments/{campingId}")
     */
    public function getComment(int $commentId, CommentRepository $commentRepository, SerializerService $serializer)
    {
        $comment=$commentRepository->find($commentId);
        $comment=$serializer->serializeData($comment);
        return $comment;
    }

    /**
     * Create a new Comment resource
     * @Rest\Post("/comments")
     * @param Request $request
     */
    public function postComment(HttpFoundationRequest $request, EntityManagerInterface $manager)
    {
        $comment=new Comment();
        $comment
            ->setText($request->get('text'))
        ;
        $manager->persist($comment);
        $manager->flush();
        $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
        return $response;
    }

    /**
     * Changes a Comment resource
     * @Rest\Put("/comments/{commentId}")
     */
    public function putComment(int $commentId, HttpFoundationRequest $request, CommentRepository $commentRepository, EntityManagerInterface $manager)
    {
        $comment=$commentRepository->findOneById($commentId);
        if($comment)
        {
            $comment
                ->setText($request->get('text'))
            ;
            $manager->persist($comment);
            $manager->flush();
        }
        $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
        return $response;
    }

    /**
     * Delete a Comment resource
     * @Rest\Delete("/comments/{commentId}")
     */
    public function deleteComment(int $commentId, CommentRepository $commentRepository, EntityManagerInterface $manager)
    {
        $comment=$commentRepository->findOneById($commentId);
        if($comment)
        {
            $manager->remove($comment);
            $manager->flush();
        }
        $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
        return $response;
    }
}
