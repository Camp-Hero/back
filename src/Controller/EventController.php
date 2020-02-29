<?php

    namespace App\Controller;

    use App\Entity\Event;
    use App\Entity\User;
    use App\Entity\Camping;
    use App\Repository\EventRepository;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Service\SerializerService;
    use Doctrine\ORM\EntityManagerInterface;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
    use Symfony\Component\HttpFoundation\Response;

class EventController extends AbstractController
    {
        /**
         * Retrieves all Events
         * @Rest\View()
         * @Rest\Get("/events")
         */
        public function getEvents(EventRepository $eventRepository, SerializerService $serializer)
        {
            $event=$eventRepository->findAllEvents();
            return $event;     
        }

        /**
         * Retrieves an Event according to the id 
         * @Rest\View()
         * @Rest\Get("/events/{eventId}")
         */
        public function getEvent(int $eventId, EventRepository $eventRepository, SerializerService $serializer)
        {
            $events=$eventRepository->find($eventId);
            var_dump($events);
            $events=$serializer->serializeData($events);
            return $events;
        }

        /**
         * Retrieves an Event according to the user
         * @Rest\View()
         * @Rest\Get("/users/{userId}/events")
         */
        public function getEventByUser(int $userId, SerializerService $serializer)
        {
            $userRepository=$this->getDoctrine()->getRepository(User::class);
            $user=$userRepository->find($userId);
            $events=$serializer->serializeData($user->getEvents());
            return $events;
        }

        /**
         * Retrieves an Event according to the camping
         * @Rest\View()
         * @Rest\Get("/campings/{campingId}/events")
         */
        public function getEventByCamping(int $campingId, SerializerService $serializer)
        {
            $campingRepository=$this->getDoctrine()->getRepository(Camping::class);
            $camping=$campingRepository->find($campingId);
            $events=$serializer->serializeData($camping->getEvents());
            return $events;
        }

        /**
         * Retrieves an Event according to the id
         * @Rest\View()
         * @Rest\Post("/campings/{campingId}/events")
         */
        public function postEvent(int $campingId, HttpFoundationRequest $request, EntityManagerInterface $manager)
        {
            $campingRepository=$this->getDoctrine()->getRepository(Camping::class);
            $userRepository=$this->getDoctrine()->getRepository(User::class);
            $camping=$campingRepository->find($campingId);
            $token=$request->headers->get("authorization");
            $tokenParts = explode(".", $token);  
            $tokenHeader = base64_decode($tokenParts[0]);
            $tokenPayload = base64_decode($tokenParts[1]);
            $jwtHeader = json_decode($tokenHeader);
            $jwtPayload = json_decode($tokenPayload);
            $user=$jwtPayload->username;
            $user=$userRepository->findOneBy(array('username' => $user));
            $userId=$user->getId();
            $user=$userRepository->find($userId);
            $event=new Event();
            $event
                ->setName($request->get('name'))
                ->setPresentation($request->get('presentation'))
                ->setBeginDate($request->get('begin_date'))
                ->setUser($user)
                ->setEndDate($request->get('end_date'))
                ->setCamping($camping)
            ;
            $manager->persist($event);
            $manager->flush();
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;
        }

        /**
         * Retrieves an Event according to the id
         * @Rest\View()
         * @Rest\Put("/events/{eventId}")
         */
        public function putEvent(int $eventId, HttpFoundationRequest $request, EntityManagerInterface $manager, EventRepository $eventRepository)
        {
            $event=$eventRepository->findOneById($eventId);
            $event
                ->setName($request->get('name'))
                ->setPresentation($request->get('presentation'))
                ->setBeginDate($request->get('beginDate'))
                ->setEndDate($request->get('endDate'))
            ;
            $manager->persist($event);
            $manager->flush();
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;
        }

        /**
         * Retrieves an Event according to the id
         * @Rest\View()
         * @Rest\Delete("/events/{eventId}")
         */
        public function deleteEvent(int $eventId, HttpFoundationRequest $request, EntityManagerInterface $manager, EventRepository $eventRepository)
        {

            /* Suppression Commentaires à ajouter */

            $event=$eventRepository->findOneById($eventId);
            if($event)
            {
                $manager->remove($event);
                $manager->flush();
            }
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;
        }
    }
