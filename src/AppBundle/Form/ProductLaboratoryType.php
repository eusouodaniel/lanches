<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductLaboratoryType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('laboratory')
                ->add('quantidade')
                ->add('factoryValue', TextType::class, array('required' => false))
                ->add('costValue', TextType::class, array('required' => false))
                ->add('discountValue', TextType::class, array('required' => false))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProductLaboratory'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'product_laboratory';
    }

}
