<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Asociacion;
use App\Entity\Categoria;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class)
            ->add('apellido', TextType::class)
            ->add('dni', TextType::class)
            ->add('telefono', TextType::class)
            ->add('email', EmailType::class)
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor ingrese una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Su contraseña debe tener al menos {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('asociacion', EntityType::class, [
                'class' => Asociacion::class,
                'attr' => [
                    'data-widget' => 'select2',
                ],
            ])
            ->add('pais', TextType::class)
            ->add('carreras', TextType::class)
            ->add('anioCursado', NumberType::class)
            ->add('obraSocial', TextType::class)
            ->add('grupoSanguineo', TextType::class)
            ->add('restriccionAlimentaria', TextType::class)
            ->add('alergias', TextType::class)
            ->add('enfermedadesCronicas', TextType::class)
            ->add('discapacidad', ChoiceType::class, [
                'choices' => [
                    'No' => 'No',
                    'Si' => 'Si',
                ],
            ])
            ->add('discapacidadDescripcion', TextType::class, [
                'label' => 'Cual',
                'required' => false,
            ])
            ->add('manoHabil', ChoiceType::class, [
                'expanded' => true,
                'choices' => [
                    'Zurdo' => 'Zurdo',
                    'Diestro' => 'Diestro',
                ],
            ])
            ->add('categoria', EntityType::class, [
                'class' => Categoria::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.congreso', 'c')
                        ->where('c.activo = true')
                        ->orderBy('u.id', 'DESC');
                },
                'attr' => [
                    'data-widget' => 'select2',
                ],
            ])
            ->add('realizaVisitas', CheckboxType::class, [
                'required' => false,
                'label' => 'Realiza visitas',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
