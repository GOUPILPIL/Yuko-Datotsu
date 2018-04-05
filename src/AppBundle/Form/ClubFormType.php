<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 3/30/18
 * Time: 4:39 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ClubFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('address', TextType::class)
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)

            ->add('save', SubmitType::class, array('label' => 'Save name'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Club'
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_club';
    }

}