<?php

namespace MyShop\DefaultBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model', TextType::class, [
                'label' => 'Модель товара: '
            ])
            ->add('productCode')
            ->add('price', NumberType::class, [
                "label" => 'Цена товара: '
            ])
            ->add('shortDescription', TextType::class,[
                'label' => 'Короткое описание: '
            ])
            ->add('description', TextType::class,[
                'label' => 'Подробное описание: '
            ])
            ->add('count', NumberType::class, [
                'label' => 'Количество товара: '
            ])
            ->add('dateStockStart', DateType::class, [
                'label' => 'Начала акции: '
            ])
            ->add('dateStockEnd', DateType::class, [
                'label' => 'Конец акции: '
            ])
            ->add('stockStatus', TextType::class, [
                'label' => 'Статус акции: '
            ])
            ->add('category', EntityType::class, [
                "class" => "MyShopDefaultBundle:Category",
                "choice_label" => "name",
                "label" => "Категория: "
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'MyShop\DefaultBundle\Entity\Product'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'myshop_defaultbundle_product';
    }


}
