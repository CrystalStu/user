<?php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class API extends AbstractController {

    /**
     * @Route("/api/{key}/getUsername")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUsername($key) {
        $user = $this->getUserFromKey($key);
        if (!isset($user)) return new Response(null, 404);
        return new Response($user->getUsername());
    }

    /**
     * @Route("/api/{key}/getEmail")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getEmail($key) {
        $user = $this->getUserFromKey($key);
        if (!isset($user)) return new Response(null, 404);
        return new Response($user->getEmail());
    }

    /**
     * @Route("/api/{key}/getId")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getId($key) {
        $user = $this->getUserFromKey($key);
        if (!isset($user)) return new Response(null, 404);
        return new Response($user->getId());
    }

    /**
     * @Route("/api/{key}/getUser")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getUserJson($key) {
        $user = $this->getUserFromKey($key);
        if (!isset($user)) return new Response(null, 404);
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($user, 'json');
        return new Response($json);
    }

    /**
     * @Route("/api/getKeyFromLogged")
     */
    public function getKeyFromLogged() {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return new Response($user->getApiKey());
    }

    /**
     * @param $key
     * @return User|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function getUserFromKey($key) {
        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->findOneByApiKey($key);
        return $user;
    }
}