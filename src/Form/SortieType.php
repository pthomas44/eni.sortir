<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\CampusRepository;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Choice;

class SortieType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', null, ['label'         => 'Nom de la sortie'])
            ->add('dateHeureDebut', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label'         => 'Début de la sortie',
                    'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('duree', null, ['label'         => 'Durée'])
            ->add('dateLimiteInscription', DateTimeType::class, [
                    'widget' => 'single_text',
                    'label' => 'Date limite d\'inscription',
                    'attr' => ['class' => 'js-datepicker'],
            ])
            ->add('nbInscriptionsMax', null, ['label' => 'Maximum de participants'])
            ->add('infosSortie', null, ['label'=> 'Détails de la sortie'])
            ->add('lieu', EntityType::class,[
             'class' => Lieu::class,
                'choice_label' => 'nom'
            ])

            ->add('ville', EntityType::class,[
                'class' => Ville::class,
                'choice_label' => function ($VilleEtCp) {
                    return $VilleEtCp->getNomEtCp();},
                'mapped'=> false,
                    ])

        ;

}

            //->add('validerLieu', SubmitType::class, ['label'=> 'Valider le lieu'])
            //->add('validerForm', SubmitType::class, ['label'=> 'Valider le formulaire'])


            //, null, ['required'=>false]


            // ------------ TEST 1 ------------

           /* ->add('lieu', EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'rue',
                'mapped'=>false,
                'attr' =>
                    ['disabled' => 'disabled']

            ])*/

            // ------------ TEST 2 (champs imbriqués, YT) ------------
        /*
        $builder ->get('lieu')->addEventListener(
            FormEvents::POST_SUBMIT,
            //callback, fonction exécutée à l'événement
            function (FormEvents $event){
                $form = $event->getForm();
                $form->add('rue', EntityType::class, [
                    'class' => Lieu::class,
                ]);
            }
        );*/

        // ------------ champ non modifiable ------------
        //<input type="text" value="" disabled />

        // ------------ TEST 3 ------------
            /*
            ->add('rue', EntityType::class,[
                'class' => Lieu::class,
                'choice_label' => 'rue',
            ])*/

        //$builder->get('lieu')->

            /*->add('ville', VilleType::class,  array(

                'class' => Ville::class,
                //Attribut utilisé pour l'affichage
                'choice_label' => 'nom',

                //Fait une requête particulière
                'query_builder' => function (VilleRepository $er) {
                    return $er->createQueryBuilder('v')
                        ->orderBy('v.ville', 'ASC');
                }
                ))*/





    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
