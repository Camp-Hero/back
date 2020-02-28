<?php

    namespace App\Controller;

    use App\Entity\Camping;
    use App\Repository\CampingRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Service\SerializerService;
    use Doctrine\ORM\EntityManagerInterface;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
    use Symfony\Component\HttpFoundation\Response;

class CampingController extends AbstractController
{
        /**
         * Retrieves all Camping
         * @Rest\View()
         * @Rest\Get("/campings")
         */
        public function getCampings(CampingRepository $campingRepository, SerializerService $serializer)
        {
            $campings=$campingRepository->findAll();
            $campings=$serializer->serializeData($campings);
            return $campings;
        }

        /**
         * Retrieves a Camping according to the id 
         * @Rest\View()
         * @Rest\Get("/campings/{campingId}")
         */
        public function getCamping(int $campingId, CampingRepository $campingRepository, SerializerService $serializer)
        {
           $camping=$campingRepository->find($campingId);
           $camping=$serializer->serializeData($camping);
           return $camping;     
        }

        /**
         * Create a new Camping resource
         * @Rest\Post("/campings")
         * @param Request $request
         */
        public function postCamping(HttpFoundationRequest $request, EntityManagerInterface $manager)
        {
            $camping=new Camping();
            $camping
                ->setName($request->get('name'))
                ->setAddress($request->get('address'))
                ->setPostalCode($request->get('postalcode'))
                ->setCity($request->get('city'))
                ->setPhone($request->get('phone'))
            ;
            $manager->persist($camping);
            $manager->flush();
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;
        }    

        /**
         * Changes a Camping resource
         * @Rest\Put("/campings/{campingId}")
         */
        public function putCamping(int $campingId, HttpFoundationRequest $request, CampingRepository $campingRepository, EntityManagerInterface $manager)
        {
            $camping=$campingRepository->findOneById($campingId);
            if($camping)
            {
                $camping
                    ->setName($request->get('name'))
                    ->setAddress($request->get('address'))
                    ->setPostalCode($request->get('postalcode'))
                    ->setCity($request->get('city'))
                    ->setPhone($request->get('phone'))
                ;
                $manager->persist($camping);
                $manager->flush();
            }
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response; 
        }

        /**
         * Delete a Camping resource
         * @Rest\Delete("/campings/{campingId}")
         */
        public function deleteCamping(int $campingId, CampingRepository $campingRepository, EntityManagerInterface $manager)
        {
            $camping=$campingRepository->findOneById($campingId);
            if($camping)
            {
                $manager->remove($camping);
                $manager->flush();
            }
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response; 
        }
    }
