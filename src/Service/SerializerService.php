<?php 

    namespace App\Service;

    use Symfony\Component\Serializer\Encoder\JsonEncoder;
    use Symfony\Component\Serializer\Encoder\XmlEncoder;
    use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
    use Symfony\Component\Serializer\Serializer;
    use Symfony\Component\HttpFoundation\Response;

    class SerializerService
    {
        public function serializeData($data)
        {
            $encoders = [new XmlEncoder(), new JsonEncoder()];
            $normalizers = [new ObjectNormalizer()];
            $serializer = new Serializer($normalizers, $encoders);
            $jsonContent=$serializer->serialize($data, 'json');
            $response=new Response();
            $response->setContent($jsonContent);
            $response->headers->set('Content-Type', 'application/json');
            return $response;  
        }
    }