<?php
/**
 * Created by PhpStorm.
 * User: Zephor
 * Date: 3/26/18
 * Time: 4:19 PM
 */

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$builder
          //  ->remove('username');
          //  ->add('reputation');
    }
    public function getParent()
    {
        return BaseRegistrationFormType::class;
    }
}