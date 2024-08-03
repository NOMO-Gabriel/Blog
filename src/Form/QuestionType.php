<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Service;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
=======
>>>>>>> origin-old/main
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('title',TextType::class,[
                'label' => 'titre de la question',
                'required' => true,
            ])
            ->add('content',TextareaType::class,[
                'label' => 'contenu de la question',
                'required' => true,
            ])
=======
            ->add('title')
            ->add('content')
>>>>>>> origin-old/main
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
            ])
<<<<<<< HEAD
            ->add('send',SubmitType::class,[
                'label' => 'poser votre question',
                'attr'=> ['class'=>'btn bg-info rounded']
            ])
=======
>>>>>>> origin-old/main
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
