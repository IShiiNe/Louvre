<?php

namespace OP\TradeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name',     TextType::class, array(
                                        'label_attr' => array(
                                                'class' => 'col-sm-2 control-label'),
                                        'label' => 'Nom et prénom',
                                        'attr' => array(
                                                'class' => 'name'),
                                        ))
      ->add('country', CountryType::class, array(
                                        'preferred_choices' => array("FR"),
                                        'label_attr' => array(
                                            'class' => 'col-sm-2 control-label'),
                                        'label' => 'Pays'))
      ->add('dateBirth',    BirthdayType::class, array(
									    'widget' => 'choice',
									    'format' => 'dd-MM-yyyy',
                                        'label_attr' => array(
                                              'class' => 'col-sm-2 control-label'),
                                        'label' => 'Date de naissance',
                                        'input' => 'string'))
      ->add('reduced', CheckboxType::class, array(
                                        'required' => false,
                                        'label_attr' => array(
                                              'class' => 'col-sm-2 control-label'),
                                        'label' => 'Tarif réduit'));
  }
  /**
   * {@inheritdoc}
   */
  public function configureOptions(OptionsResolver $resolver)
  {
        $resolver->setDefaults(array(
            'data_class' => 'OP\TradeBundle\Entity\Ticket'
        ));
  }
  /**
   * {@inheritdoc}
   */
  public function getBlockPrefix()
  {
        return 'op_tradebundle_ticket';
  }
}
