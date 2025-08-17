<?php

namespace App\Form;

use App\Enum\SearchType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $typeChoices = [];
        foreach (SearchType::cases() as $type) {
            $typeChoices[ucfirst($type->name)] = $type;
        }
        $builder
            ->add('name', TextType::class, [
                'label' => 'Enter your search term',
            ])
            ->add('searchType', ChoiceType::class, [
                'choices' => $typeChoices,
                'expanded' => true,
                'multiple' => false,
                'choice_label' => fn(SearchType $type) => ucFirst($type->name),
                'choice_value' => fn(?SearchType $type) => $type?->value,
                'row_attr' => ['class' => 'd-flex flex-wrap'],
                'choice_attr' => fn() => ['class' => 'form-check-inline'],
                'placeholder' => 'E.G Pikachu or Fire Blast'
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
