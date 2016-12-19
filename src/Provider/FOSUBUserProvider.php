<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 4/15/2016
 * Time: 04:05 PM
 */
namespace CustomizedHwi\HwiBundle\Provider;


use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\User\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CustomizedHwi\HwiBundle\Events\AuthRegisterEvent;
use CustomizedHwi\HwiBundle\Events\CustomException;
use CustomizedHwi\HwiBundle\Entity\AdditionalRegistrationData;

class FOSUBUserProvider extends BaseClass
{
    private $container;
    private $userSession;
    private $userAdditionalSession;
    /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager FOSUB user provider.
     * @param array                $properties  Property mapping.
     */
    public function __construct(
        UserManagerInterface $userManager,
        array $properties,
        ContainerInterface $container)
    {
        $this->userManager = $userManager;
        $this->properties  = array_merge($this->properties, $properties);
        $this->accessor    = PropertyAccess::createPropertyAccessor();
        $this->container = $container;
        $this->userSession = $this->container->getParameter('unstable_user_session');
        $this->userAdditionalSession = $this->container->getParameter('unstable_user_additional_session');
    }
    /**
     * {@inheritDoc}
     */
    public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $service = $response->getResourceOwner()->getName();
        $setter = 'set'.ucfirst($service);
        $getter = 'get'.ucfirst($service);
        $getter_id = $getter.'Id';
        $getter_token = $getter.'AccessToken';
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
        if(
            !$user->$getter_id()&&
            !$user->$getter_token()
        ){
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $this->userManager->updateUser($user);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            switch ($service){
                case "vkontakte":
                    $user = $this->setVkUser($response);
                    break;
                case "facebook":
                    $user= $this->setFbUser($response);
                    break;
                case "google":
                    $user = $this->setGoogleUser($response);
                    break;
            }
            $additionalData = new AdditionalRegistrationData();
            $additionalData->setSecond($response->getUsername());
            $additionalData->setFirst($response->getAccessToken());
            $user->setEnabled(true);
            $user->setSocialVendor($service);
            $session = $this->container->get('session');
            $session->set($this->userSession,$user->serialize());
            $session->set($this->userAdditionalSession,$additionalData->serialize());
            throw new CustomException($user);
        }
        $user = parent::loadUserByOAuthUserResponse($response);
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
        $user->$setter($response->getAccessToken());
        return $user;
    }
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername($username)
    {
        // Compatibility with FOSUserBundle < 2.0
        if (class_exists('FOS\UserBundle\Form\Handler\RegistrationFormHandler')) {
            return $this->userManager->loadUserByUsername($username);
        }
        return $this->userManager->findUserByUsername($username);
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class)
    {
        $userClass = $this->userManager->getClass();

        return $userClass === $class || is_subclass_of($class, $userClass);
    }

    /**
     * Gets the property for the response.
     *
     * @param UserResponseInterface $response
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected function getProperty(UserResponseInterface $response)
    {
        $resourceOwnerName = $response->getResourceOwner()->getName();
        if (!isset($this->properties[$resourceOwnerName])) {
            throw new \RuntimeException(sprintf("No property defined for entity for resource owner '%s'.", $resourceOwnerName));
        }

        return $this->properties[$resourceOwnerName];
    }

    /**
     * @param $response - social response
     *
     * return - user entity
     * @return \FOS\UserBundle\Model\UserInterface
     */
    private function setVkUser($response)
    {
        $user = $this->userManager->createUser();
        $user->setVkAccessToken($response->getAccessToken());
        $vkResponse = $response->getResponse();
        $vkMain = $vkResponse['response'][0];
        $vkEmail = $vkResponse['email'];
        $user->setVkId($vkMain['uid']);
        $user->setUsername($vkMain["first_name"]." ".$vkMain["last_name"]);
        $user->setAvatar($this->setUserAvatar($vkMain["photo_medium"]));
        $user->setEmail($vkEmail);
        return $user;
    }

    /**
     * @param $response - social response
     * @return \FOS\UserBundle\Model\UserInterface
     */
    private function setGoogleUser($response)
    {
        $user = $this->userManager->createUser();
        $user->setGoogleAccessToken($response->getAccessToken());
        $googleResponse = $response->getResponse();
        $user->setUsername($googleResponse["name"]);
        $user->setGoogleId($googleResponse["id"]);
        $user->setAvatar($this->setUserAvatar($googleResponse["picture"]));
        $user->setEmail($googleResponse["email"]);
        return $user;
    }
    private function setFbUser($response)
    {
        $user = $this->userManager->createUser();
        $user->setFacebookAccessToken($response->getAccessToken());
        $fbResponse = $response->getResponse();
        $user->setUsername($fbResponse["name"]);
        $user->setFacebookId($fbResponse["id"]);
        $user->setAvatar($this->setUserAvatar($fbResponse["picture"]["data"]["url"]));
        $user->setEmail($fbResponse["email"]);
        return $user;
    }
    private function setUserAvatar($avatar){
        $config = $this->container->getParameter('user_avatar_type');
        $upload_config = $this->container->getParameter('site_upload.types');
        if(
            !$upload_config||
            !isset($upload_config[$config])
        ){
            return $avatar;
        }
        $arr=[
            'path'=>$avatar,
            'file_type'=>$config,
            'field'=>'avatar'
        ];
        return json_encode($arr);
    }
}