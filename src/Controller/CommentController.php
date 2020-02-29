<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Event;
use App\Repository\CommentRepository;
use App\Entity\User;
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
     * @Rest\Get("/comments/{commentId}")
     */
    public function getComment(int $commentId, CommentRepository $commentRepository, SerializerService $serializer)
    {
        $comment=$commentRepository->find($commentId);
        $comment=$serializer->serializeData($comment);
        return $comment;
    }

    /**
     * Retrieves a Comment according to the event
     * @Rest\View()
     * @Rest\Get("/events/{eventId}/comments")
     */
   /* public function getCommentByEvent(int $eventId, SerializerService $serializer)
    {
        $eventRepository=$this->getDoctrine()->getRepository(Event::class);
        $event=$eventRepository->find($eventId);
        $comments=$serializer->serializeData($event->getComments());
        return $comments;
    }*/
    /**
     * Create a new Comment resource
     * @Rest\Post("/events/{eventId}/users/{userId}/comments")
     * @param Request $request
     */
    /*public function postComment(int $eventId, int $userId, HttpFoundationRequest $request, EntityManagerInterface $manager)
    {
        $eventRepository=$this->getDoctrine()->getRepository(Event::class);
        $event=$eventRepository->find($eventId);
        $userRepository=$this->getDoctrine()->getRepository(User::class);
        $user=$userRepository->find($userId);
        $comment=new Comment();
        $comment
            ->setText($request->get('text'))
            ->setUser($user)
            ->setEvent($event)
        ;
        $manager->persist($comment);
        $manager->flush();
        $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
        return $response;
    }*/

    /**
     * Changes a Comment resource
     * @Rest\Put("/comments/{commentId}")
     */
    /*public function putComment(int $commentId, HttpFoundationRequest $request, CommentRepository $commentRepository, EntityManagerInterface $manager)
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
    }*/

    /**
     * Delete a Comment resource
     * @Rest\Delete("/comments/{commentId}")
     */
   /* public function deleteComment(int $commentId, CommentRepository $commentRepository, EntityManagerInterface $manager)
    {
        $comment=$commentRepository->findOneById($commentId);
       /* if($this->getUser->getId()=$comment->getUser()->getId())
        {*/
            /*if($comment)
            {
                $manager->remove($comment);
                $manager->flush();
            }
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;*/
        //}
        
        
    //}
}
