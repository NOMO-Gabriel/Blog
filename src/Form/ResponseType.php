<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Response;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
=======
>>>>>>> origin-old/main
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'title',
                'disabled' => true,
            ])
            ->add('content',TextareaType::class,[
                'label' => 'votre reponse' ,
                'required' => true,
            ])
            ->add('submit',SubmitType::class,[
                'label'=> 'soumettre',
                'attr'=> ['class'=>'btn bg-info rounded']
            ] )
=======
            ->add('content')
            ->add('question', EntityType::class, [
                'class' => Question::class,
                'choice_label' => 'id',
            ])
>>>>>>> origin-old/main
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
        ]);
    }
}
