<?php

namespace OP\TradeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use OP\TradeBundle\Form\TicketType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('visiteDate', TextType::class, array(
                'label_attr' => array(
                    'class' => 'col-sm-2 control-label'),
                'label' => 'Date de la visite')
            )
            ->add('tickets', CollectionType::class, array(
                'entry_type'   => TicketType::class,
                'allow_add'    => true,
                'allow_delete' => true
            ))
            ->add('save',      SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OP\TradeBundle\Entity\Commande'
        ));
    }

}