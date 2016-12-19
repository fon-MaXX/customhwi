<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 4/28/2016
 * Time: 05:14 PM
 */
namespace CustomizedHwi\HwiBundle\Events;


class CustomException extends \Exception
{
    private $user;

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }
    public function __construct($user)
    {
       $this->user = $user;
    }
}