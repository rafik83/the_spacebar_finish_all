<?php
/**
 * Created by PhpStorm.
 * User: rafik
 * Date: 28/06/19
 * Time: 15:05
 */

namespace App\Form;


use App\Form\DataTransformer\EmailToUserTransformer;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;


class UserSelectTextType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $UserRepository;
    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(UserRepository $UserRepository, RouterInterface $router)
    {
        $this->UserRepository = $UserRepository;
        $this->router = $router;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
      $builder->addModelTransformer(new EmailToUserTransformer($this->UserRepository));
    }


    public function getParent()
    {
        return TextType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'invalid_message' => 'Hm, user not found!',
//            'attr' => [
//                'class'=> 'js-user-autocomplete',
//                'data-autocomplete-url'=> $this->router->generate('admin_utility_users')
//            ]
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $attr = $view->vars['attr'];
        $class = isset($attr['class']) ? $attr['class'].' ' : '';
        $class.= 'js-user-autocomplete' ;
        $attr['class'] = $class;
        $attr['data-autocomplete-url'] = $this->router->generate('admin_utility_users');
        $view->vars['attr']= $attr;
    }


}