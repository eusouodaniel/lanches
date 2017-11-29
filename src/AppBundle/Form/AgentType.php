<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AgentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('email')
            ->add('celphone')
            ->add('phone')
            ->add('perfumery')
            ->add('agentregions', CollectionType::class, array(
                'entry_type' => AgentRegionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                ));
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Agent'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'agent';
    }
}
