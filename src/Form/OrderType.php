<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class, ['label' => 'Address'])
            ->add('phone', IntegerType::class, ['label' => 'Phone number'])
            ->add('comments', TextareaType::class, ['required' => false, 'label' => 'Comments'])
            ->add('firstname', TextType::class, ['label' => 'First name'])
            ->add('lastname', TextType::class, ['label' => 'Last name'])
            ->add('email', EmailType::class, ['attr' => ['autocomplete' => 'email'], 'label' => 'Email address'])
            ->add('submit', SubmitType::class, ['label' => 'Submit', 'attr' => ['class' => 'btn btn-primary']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
