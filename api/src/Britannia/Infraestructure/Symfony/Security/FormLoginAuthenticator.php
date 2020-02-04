<?php

namespace Britannia\Infraestructure\Symfony\Security;

use Britannia\Domain\Entity\Staff\StaffMember;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(EntityManagerInterface $entityManager,
                                UrlGeneratorInterface $urlGenerator,
                                CsrfTokenManagerInterface $csrfTokenManager,
                                EncoderFactoryInterface $encoderFactory
    )
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->encoderFactory = $encoderFactory;
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'userNameOrEmail' => $request->request->get('userNameOrEmail'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['userNameOrEmail']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(StaffMember::class)
            ->loadUserByUsername($credentials['userNameOrEmail']);

        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Username or Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['password'] ?? null;
        $encoder = $this->encoderFactory->getEncoder($user);
        $encodedPassword = $encoder->encodePassword($password, $user->getSalt());

        return $encodedPassword === $user->getPassword();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetPath = $this->getRedirectPath($token);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

    }

    private function getRedirectPath(TokenInterface $token): string
    {
        if ($token->getUser()->isTeacher()) {
            return $this->urlGenerator->generate('admin_britannia_domain_lesson_lesson_list');

        }

        return $this->urlGenerator->generate('sonata_admin_dashboard');
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate('app_login');
    }
}
