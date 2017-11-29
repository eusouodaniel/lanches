<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LaboratoryType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name')
                ->add('avatar', 'hidden', array())
                ->add('avatarTemp', 'file', array(
                    'label' => 'Imagem de Banner',
                    'required' => false
                ))
                ->add('minimunValue', TextType::class, array('required' => false))
                ->add('billingRequirements', TextType::class, array('required' => false))
                ->add('firstDistributor')
                ->add('secondDistributor')
                ->add('thirdDistributor')
                ->add('active')
                ->add('laboratoryagents', CollectionType::class, array(
                    'entry_type' => LaboratoryAgentType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Laboratory'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'laboratory';
    }

}
