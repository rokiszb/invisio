<?php

namespace App\Controller;

use App\Service\ProductResponseResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    public function __construct(private ProductResponseResolver $resolver)
    {
    }

    #[Route('/parameter', name: 'api')]
    public function index(Request $request): Response
    {
        $response = $this->resolver->getProductResponse($request->get('parameter1'), $request->get('parameter2'));

        return $this->json($response);
    }
}
