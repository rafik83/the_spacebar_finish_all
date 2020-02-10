<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 26/06/19
 * Time: 18:23
 */

namespace App\Form;


use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{


    /**
     * @var UserRepository
     */
    private $userRepo;

    public function __construct(UserRepository $userRepo)
    {

        $this->userRepo = $userRepo;

    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        /** @var Article|null $article */
        $article = $options['data'] ?? null;
        $isEdit = $article && $article->getId();
//        $location = $article ? $article->getLocation() : null;
//        dd($location);


       $builder
           ->add('title',TextType::class,[
               'help' => 'Choose something catchy!'
           ])

//           ->add('content')
           ->add('content',null,[
               'attr'=> ['rows' =>15]
           ])

           ->add('author',UserSelectTextType::class,[
               'disabled'=> $isEdit
               ])
           ->add('location',ChoiceType::class,[

               'placeholder' => 'Choose a location',

               'choices' => [

                   'The Solar System' => 'solar_system',
                   'Near a star' => 'star',
                   'Interstellar Space' => 'interstellar_space'
               ],
               'required'=> false
           ]);

//       if ($location){
//           $builder->add('specificLocationName',ChoiceType::class,[
//               'placeholder' => 'Where exactly ?',
//               'choices' => $this->getLocationNameChoices($location),
//               'required'=> false
//           ]);
//       }

//           ->add('author',UserSelectTextType::class,[
//               'invalid_message' => 'different message'
//           ])



//           ->add('author',EntityType::class,[
//               'class'=> User::class,
//               'choice_label'=> function(User $user){
//               return sprintf('(%d) %s',$user->getId(),$user->getEmail());
//               },
//               'placeholder'=> 'Choose an author',
//               'choices'=> $this->userRepo->findAllEmailAlphabetical(),
//               'invalid_message'=> 'Symfony is too smart for your hacking!'
//           ]);
           ;

       if ($options['include_published_at']){
           $builder
               ->add('publishedAt',DateTimeType::class,[
                   'widget'=> 'single_text'
               ]);
       }

       $builder->addEventListener(
           FormEvents::POST_SET_DATA,
           function (FormEvent $event){
               $data = $event->getData();
               if (!$data){
                   return ;
               }

               $this->setupSpecificLocationNameField(
                   $event->getForm(),
                   $data->getLocation()

               );

           }

       );

       $builder->get('location')->addEventListener(
          FormEvents::POST_SUBMIT,
          function (FormEvent $event){
              $form = $event->getForm();
              $this->setupSpecificLocationNameField(
                  $form->getParent(),
                  $event->getData()// ou $form->getData()
              );
          }


       );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
         'data_class'=> Article::class,
            'include_published_at'=> false,
        ]);
    }


    private function getLocationNameChoices(string $location)
    {
        $planet = [
            'Mercury',
            'Venus',
            'Earth',
            'Mars',
            'Jupiter',
            'Saturn',
            'Uranus',
            'Neptune',

        ];

        $stars = [
            'Polaris',
            'Sirius',
            'Alpha Centauari A',
            'Alpha Centauari B',
            'Betelgeuse',
            'Rigel',
            'Other',

        ];

        $locationNameChoices = [
            'solar_system'=> array_combine($planet,$planet),
            'star'=> array_combine($stars,$stars),
            'interstellar_space' => null,

        ];

        return $locationNameChoices[$location] ?? null;

    }


    private function setupSpecificLocationNameField(FormInterface $form, ?string $location){

        if (null === $location){
            $form->remove('specificLocationName');
            return ;
        }
        $choices = $this->getLocationNameChoices($location);
        if (null === $choices){
            $form->remove('specificLocationName');
            return ;
        }

        $form->add('specificLocationName',ChoiceType::class,[
            'placeholder' => 'Where exactly ?',
            'choices' => $choices,
            'required'=> false
        ]);

    }
}