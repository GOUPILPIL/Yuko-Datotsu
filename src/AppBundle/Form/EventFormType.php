<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 3/30/18
 * Time: 4:39 PM
 */

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;


class EventFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class)
            ->add('description', CKEditorType::class, array('config' => array('uiColor' => '#ffffff', 'toolbar' => 'standard')))
            ->add('address', TextType::class)
            ->add('lat', HiddenType::class)
            ->add('lng', HiddenType::class)
           ->add('categories', EntityType::class, array(

               'class'        => 'AppBundle:Category',

               'choice_label' => 'name',

               'multiple'     => true,
           ))
            ->add('dateStart', DateType::class, array(
                'widget' => 'single_text'
            ))
            ->add('save', SubmitType::class, array('label' => 'Save name'));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Event'
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_event';
    }

}