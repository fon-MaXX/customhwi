<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 4/28/2016
 * Time: 12:51 PM
 */
namespace CustomizedHwi\HwiBundle\Events;

use Site\UserBundle\SiteUserBundle;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ExceptionListener
{
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    public function onKernelException($event)
    {
        $exception = $event->getException();
        if($exception instanceof \Site\UserBundle\Events\CustomException){
            $url = $this->container->get('router')->generate(
                'fos_user_registration_register',
                array(),
                true
                );
            $event->setResponse(new RedirectResponse($url));
        }
    }
}