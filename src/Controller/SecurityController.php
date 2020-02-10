<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Model\UserRegistrationFormModel;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }


    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('well be intercepted before getting here');
    }


    /**
     * @Route("/register", name="app_register")
     */
    public  function register(Request $request,UserPasswordEncoderInterface $passwordEncoder,GuardAuthenticatorHandler $gardhandler,LoginFormAuthenticator $formAuthenticator)
    {


        $form= $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            /** @var  UserRegistrationFormModel $userModel */
            $userModel = $form->getData();
            $user = new User();

            $user->setEmail($userModel->email);

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $userModel->plainPassword
//                    $form['plainPassword']->getData()
                )
            );
            if ($userModel->agreeTerms === true){//$form['agreeTerms']->getData()
                //$user->setAgreedTermsAt(new \DateTime());//1 er solution

                //2 éme solution
                $user->agreeTerms();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $gardhandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render('security/register.html.twig',[
            'registrationForm' => $form->createView()
        ]);
    }





    /**
     * @Route("/old/register", name="app_old_register")
     */
    public  function registerOld(Request $request,UserPasswordEncoderInterface $passwordEncoder,GuardAuthenticatorHandler $gardhandler,LoginFormAuthenticator $formAuthenticator)
    {


        $form= $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {

            $user = $form->getData();
//            dd($user);
//            $user->setEmail($request->request->get('email'));
//            $user->setFirstName('Mystery');
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form['plainPassword']->getData()
//                    $user->getPassword()
                )
            );
            if ($form['agreeTerms']->getData()=== true){
                //$user->setAgreedTermsAt(new \DateTime());//1 er solution

                //2 éme solution
                $user->agreeTerms();
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $gardhandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $formAuthenticator,
                'main'
            );
        }

        return $this->render('security/register.html.twig',[
            'registrationForm' => $form->createView()
        ]);
    }




}
