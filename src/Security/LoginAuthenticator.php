<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractFormLoginAuthenticator {
    use TargetPathTrait;

    private $entityManager;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $apiKey;
    private $left;
    private $domain;

    public function __construct(EntityManagerInterface $entityManager,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordEncoderInterface $passwordEncoder) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request) {
        return 'app_login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request) {
        $credentials = [
            'username' => $request->request->get('username'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(Security::LAST_USERNAME, $credentials['username']);

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['username' => $credentials['username']]);
        if (!$user) $user = $this->entityManager->getRepository(User::class)
                                                ->findOneBy(['email' => $credentials['username']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Username could not be found.');
        }

        $this->apiKey = md5($user->getUsername() . $user->getPassword() . date_timestamp_get(date_create()));
        $user->setApiKey($this->apiKey);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->router->generate('some_route'));
        if (isset($_GET['redirect'])) {
            $url = $_GET['redirect'];
            $this->left = 0;
            for ($t = 0; $t < strlen($url); $t++) {
                if ($t == strlen($url) - 1) {
                    $this->domain = substr($url, $this->left, $t - $this->left);
                    break;
                }
                if ($url[$t] == '/') {
                    if ($url[$t + 1] == '/') {
                        $t += 2;
                        $this->left = $t;
                        continue;
                    }
                    $this->domain = substr($url, $this->left, $t - $this->left);
                    break;
                }
                if ($this->left != 0 && $url[$t] == ':') {
                    $this->domain = substr($url, $this->left, $t - $this->left);
                    break;
                }
            }
            $response = new RedirectResponse($_GET['redirect']);
            $response->headers->setCookie(new Cookie("cws-user-key", $this->apiKey, time() + 3600 * 24 * 7, null, $this->domain));
            return $response;
            // return new RedirectResponse($_GET['redirect'] . (parse_url($_GET['redirect'], PHP_URL_QUERY) ? '&' : '?') . 'key=' . $this->apiKey);
        }
        return new RedirectResponse($this->router->generate('app_rootrefuse_show'));
        // return new RedirectResponse($this->router->generate('app_display_showme'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl() {
        return $this->router->generate('app_login');
    }
}