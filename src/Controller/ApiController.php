<?php

namespace App\Controller;

use App\Request\ProductConfigRequest;
use App\Service\ProductResponseResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiController extends AbstractController
{
    public function __construct(private ProductResponseResolver $resolver, private ValidatorInterface $validator)
    {
    }

    #[Route('/parameter', name: 'api', methods: ['GET'])]
    public function getConfig(Request $request): Response
    {
        $productRequest = new ProductConfigRequest();
        $productRequest->parameter1 = $request->get('parameter1');
        $productRequest->parameter2 = $request->get('parameter2');
        $violations = $this->validator->validate($productRequest);

        if ($violations->count() > 0) {
            $errors = $this->prepareViolationsMessages($violations);
            return $this->json([
                'status' => 'error',
                'violations' => $errors],
                Response::HTTP_BAD_REQUEST
            );
        }

        $response = $this->resolver->getProductResponse($productRequest);

        return $this->json($response);
    }

    private function prepareViolationsMessages(ConstraintViolationList $violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage() . "Valid choices: " . $violation->getParameters()['{{ choices }}'];
        }

        return $errors;
    }
}
