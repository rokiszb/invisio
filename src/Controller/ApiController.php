<?php

namespace App\Controller;

use App\Form\ProductType;
use App\Response\ProductChoiceResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{

    #[Route('/parameter', name: 'api')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ProductType::class);
        $param1 = $request->query->get('parameter1');
        $param2 = $request->query->get('parameter2');

        if (isset($param1) || isset($param2)) {
            $form = $form->submit($request->query->all());
        }

        return $this->json([
            'parameter1' => ProductChoiceResponse::formChoicesToArray($form->get('parameter1')->createView()->vars['choices']),
            'parameter2' => ProductChoiceResponse::formChoicesToArray($form->get('parameter2')->createView()->vars['choices']),
        ]);
    }
}
