<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    #[Route('/api/user-id', name: 'get_user_id', methods: ['GET'])]
    public function getUserId(): JsonResponse
    {
        $user = $this->getUser();
        return new JsonResponse(['userId' => $user->getId()]);
    }
}
