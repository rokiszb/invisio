<?php

namespace App\Form;

use App\Config\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function __construct(private Product $product)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod('GET');

        $builder
            ->add('parameter1', ChoiceType::class, [
                'choices'  => $this->productGroup(Product::PRODUCT_GROUP_1),
                'required'   => false,
            ])
            ->add('parameter2', ChoiceType::class, [
                'choices'  => $this->productGroup(Product::PRODUCT_GROUP_2),
                'required'   => false,
            ])
        ;

        $formModifier = function (FormInterface $form, $selected, $groupName, $group) {
            $product = $form->getData();
            $form = $form->getParent();

            if (null === $product) {
                return;
            }
            $form->remove($selected);
            $form
                ->add($selected, ChoiceType::class, [
                    'choices'  => [$product],
                ]);
            $form->remove($groupName);
            $form
                ->add($groupName, ChoiceType::class, [
                    'choices'  => ($this->filterChoices($product, $group)),
                ]);
        };

        $builder->get('parameter1')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $formModifier(
                    $event->getForm(),
                    'parameter1',
                    'parameter2',
                    Product::PRODUCT_GROUP_2
                );
            }
        );

        $builder->get('parameter2')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($formModifier) {
                $formModifier(
                    $event->getForm(),
                    'parameter2',
                    'parameter1',
                    Product::PRODUCT_GROUP_1
                );
            }
        );
    }

    private function filterChoices(string $product, array $productGroup): array
    {
        if ($this->product->hasIncompatibleProducts($product)) {
            return array_diff($productGroup, [$this->product->getIncompatibleProducts($product)]);
        }

        return $productGroup;
    }

    private function productGroup(array $array): array
    {
        return array_combine($array, $array);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false
        ]);
    }
}
