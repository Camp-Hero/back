<?php

    namespace App\Controller;

    use App\Entity\User;
    use App\Entity\Event;
    use App\Repository\UserRepository;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use App\Service\SerializerService;
    use Doctrine\DBAL\DriverManager;
    use Doctrine\ORM\EntityManagerInterface;
    use FOS\RestBundle\Controller\Annotations as Rest;
    use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
    use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
    use Symfony\Component\Security\Core\User\User as UserUser;
    use Symfony\Component\Security\Core\User\UserInterface as UserEncoded;

class UserController extends AbstractController
    {
        
        /**
         * Retrieves all User
         * @Rest\View()
         * @Rest\Get("/users")
         */
        public function getUsers(UserRepository $userRepository, SerializerService $serializer)
        {
            $users=$userRepository->findAll();
            $users=$serializer->serializeData($users);
            return $users;
        }

        /**
         * Retrieves a User according to the id 
         * @Rest\View()
         * @Rest\Get("/users/{userId}")
         */
        public function getOneUser(int $userId, UserRepository $userRepository, SerializerService $serializer)
        {
           $user=$userRepository->find($userId);
           $user=$serializer->serializeData($user);
           return $user;     
        }

        /**
         * Create a new User resource
         * @Rest\Post("/users")
         * @param Request $request
         */
        public function postUser(HttpFoundationRequest $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
        {
            $user=new User();
            $plainPassword=$request->get('password');
            $encoded=$encoder->encodePassword($user, $plainPassword);
            $user
                ->setUsername($request->get('username'))
                ->setEmail($request->get('email'))
                ->setPassword($encoded)
            ;
            $manager->persist($user);
            $manager->flush();
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;
        }    

        /**
         * Changes an User resource
         * @Rest\Put("/users/{userId}")
         */
        public function putUser(int $userId, HttpFoundationRequest $request, UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager, UserRepository $userRepository)
        {
            $userPwd=new User();
            $plainPassword=$request->get('password');
            $encoded=$encoder->encodePassword($userPwd, $plainPassword);
            $user=$userRepository->findOneById($userId);
            if($user)
            {
                $user
                    ->setUsername($request->get('username'))
                    ->setEmail($request->get('email'))
                    ->setPassword($encoded)
                ;
            }
            $manager->persist($user);
            $manager->flush();
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response;
        }

        /**
         * Delete a User resource
         * @Rest\Delete("/users/{userId}")
         */
        public function deleteUser(int $userId, UserRepository $userRepository, EntityManagerInterface $manager)
        {
            /* Suppression Event et commentaires à ajouter */
            $user=$userRepository->findOneById($userId);
            if($user)
            {
                $manager->remove($user);
                $events=$manager->getRepository(Event::class)->findEventsByUser($userId);
                $manager->flush();
            }
            $response=new Response('Content', Response::HTTP_OK, ['content-type' => 'text/html']);
            return $response; 
        }
    }
