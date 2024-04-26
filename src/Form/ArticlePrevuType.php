<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\ArticlePrevu;
use App\Entity\ListeCourse;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType as TypeIntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class ArticlePrevuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('quantite', TypeIntegerType::class, [
            'constraints' => [
                new GreaterThanOrEqual([
                    'value' => 0,
                    'message' => 'La quantité ne peut pas être négative.',
                ]),
            ],
        ])
            ->add('statutAchat')

            ->add('Article', EntityType::class, [
                'class' => Article::class,
                'choice_label' => 'nom_article',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ArticlePrevu::class,
        ]);
    }
}
