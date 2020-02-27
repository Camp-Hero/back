<?php

    namespace App\Controller;

    use App\Entity\Event;
    use App\Repository\EventRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Service\SerializerService;
    use Doctrine\ORM\EntityManagerInterface;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
    use Symfony\Component\HttpFoundation\Response;

class                EventController extends AbstractController
    {
        /**
         * Retrieves all Events
         * @Rest\View()
         * @Rest\Get("/events")
         */
        public function getEvents(EventRepository $eventRepository, SerializerService $serializer)
        {
            $events=$eventRepository->findOneByName('teeest');
            var_dump($events);
            $events=$serializer->serializeData($events);
            return $events;
        }

        /**
         * Retrieves an Event according to the id 
         * @Rest\View()
         * @Rest\Get("/events/{eventId}")
         */
        public function getEvent(int $eventId, EventRepository $eventRepository, SerializerService $serializer)
        {
           $event=$eventRepository->findAllEvents();
           return $event;     
        }

        /**
         * Retrieves an Event according to the id
         * @Rest\View()
         * @Rest\Post("/events")
         */
        public function postEvent(HttpFoundationRequest $request, EntityManagerInterface $manager)
        {
            $event=new Event();
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
