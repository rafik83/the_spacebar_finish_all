<?php


namespace App\Security;

use App\Repository\UserRepository;
use PhpParser\Builder\Class_;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
//use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;


class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{


    use TargetPathTrait;

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserRepository $userRepository,RouterInterface $router,CsrfTokenManagerInterface $csrfTokenManager,UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {


        // TODO: Implement supports() method.
        return $request->attributes->get('_route') === 'app_login'

            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        // TODO: Implement getCredentials() method.
        $credentials = [
            'email'=> $request->request->get('email'),
            'password'=> $request->request->get('password'),
            'csrf_token'=> $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );


        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // TODO: Implement getUser() method.
        $token = new CsrfToken('authenticate',$credentials['csrf_token']);
        if (! $this->csrfTokenManager->isTokenValid($token)){
            throw new InvalidCsrfTokenException();
        }

        return $this->userRepository->findOneBy(['email'=> $credentials['email']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
          return $this->passwordEncoder->isPasswordValid($user,$credentials['password']);
    }



    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.

//        dd($this->getTargetPath($request->getSession(),$providerKey));
        if($targetPath = $this->getTargetPath($request->getSession(),$providerKey)){

            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->router->generate('app_homepage'));
    }

    protected function getLoginUrl()
    {
        // TODO: Implement getLoginUrl() method.
      return  $this->router->generate('app_login');
    }


}