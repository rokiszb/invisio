<?php

namespace App\Controller;

use App\Form\ProductType;
use App\Response\ProductChoiceResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
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
            if (!$form->isValid()) {
                $errors = $this->getErrorsFromForm($form);

                return $this->json([
                    'errors' => $errors
                ], 400);
            }
        }

        return $this->json([
            'parameter1' => $this->formChoicesToArray($form->get('parameter1')->createView()->vars['choices']),
            'parameter2' => $this->formChoicesToArray($form->get('parameter2')->createView()->vars['choices']),
        ]);
    }

    /**
     * Returns an associative array of validation errors
     *
     * {
     *     'firstName': 'This value is required',
     *     'subForm': {
     *         'someField': 'Invalid value'
     *     }
     * }
     *
     * @param FormInterface $form
     * @return array|string
     */
    protected function getErrorsFromForm(FormInterface $form)
    {
        foreach ($form->getErrors() as $error) {
            // only supporting 1 error per field
            // and not supporting a "field" with errors, that has more
            // fields with errors below it
            return $error->getMessage();
        }

        $errors = array();
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childError = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childError;
                }
            }
        }

        return $errors;
    }

    public function formChoicesToArray($formChoices): array
    {
        $arr = [];
        foreach ($formChoices as $choice) {
            $arr[] = $choice->value;
        }
        return $arr;
    }
}
