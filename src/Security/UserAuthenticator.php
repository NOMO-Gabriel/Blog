<?php

namespace App\Security;

<<<<<<< HEAD
use Couchbase\AuthenticationException;
=======
>>>>>>> origin-old/main
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
<<<<<<< HEAD
use Symfony\Component\Security\Core\User\UserProviderInterface;
=======
>>>>>>> origin-old/main
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
<<<<<<< HEAD
    private UserProviderInterface $userProvider;

    public function __construct(private UrlGeneratorInterface $urlGenerator,UserProviderInterface $userProvider)
    {
        $this->urlGenerator = $urlGenerator;
        $this->userProvider = $userProvider;
=======

    public function __construct(private UrlGeneratorInterface $urlGenerator)
    {
>>>>>>> origin-old/main
    }

    public function authenticate(Request $request): Passport
    {
        $username = $request->getPayload()->getString('username');
<<<<<<< HEAD
        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);
        // Récupérer l'utilisateur par le nom d'utilisateur
        $user = $this->userProvider->loadUserByIdentifier($username);
        if ($user->getIsDisabled()) {
            throw new AuthenticationException('User account is disabled.');
        }
=======

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $username);

>>>>>>> origin-old/main
        return new Passport(
            new UserBadge($username),
            new PasswordCredentials($request->getPayload()->getString('password')),
            [
                new CsrfTokenBadge('authenticate', $request->getPayload()->getString('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $username=$token->getUser()->getUserIdentifier();
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
         return new RedirectResponse($this->urlGenerator->generate('blog.home.user.index',[
             'username' => $username
         ]));

    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
