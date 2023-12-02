<?php

namespace App\Form;

use App\Entity\Artist;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArtistType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
                'attr' => ['placeholder' => 'Name'],
                'row_attr' => ['class' => 'mb-3 row'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
            ])
            ->add('profile_picture', FileType::class, [
                'label' => 'Picture',
                'row_attr' => ['class' => 'mb-3 row'],
                'label_attr' => ['class' => 'col-sm-2 col-form-label'],
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional, so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ]),
                ],
                // 'image_property' => 'picture'
            ])
            ->add('albums', CollectionType::class, [
                'entry_type' => AlbumType::class,
                'label' => 'Albums',
                // 'entry_options' => ['label' => false],
                'allow_add' => true,
                'by_reference' => false,
                'allow_delete' => true,
                'prototype' => true,
            ])
            ->add('save', SubmitType::class, ['label' => 'Create Artist'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            /*'empty_data' => function (FormInterface $form): Artist {
                return new Artist(
                    name: $form->get('name')->getData(),
                    picture: $form->get('picture')->getData()
                );
            },
            'validation_groups' => ['registration'],
            */
            'data_class' => Artist::class,
        ]);
    }
}
