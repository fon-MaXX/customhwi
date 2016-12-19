<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 5/12/2016
 * Time: 06:17 PM
 *
 * Class is using to store access token and social id while registration via oauth
 *
 * - first - social acess token
 * - second - social id
 *
 */

namespace CustomizedHwi\HwiBundle\Entity;

class AdditionalRegistrationData implements \Serializable
{
    private $first;
    private $second;
    public function getFirst()
    {
        return $this->first;
    }
    public function setFirst($first)
    {
        $this->first = $first;
        return $this;
    }
    public function getSecond()
    {
        return $this->second;
    }
    public function setSecond($second){
        $this->second = $second;
        return $second;
    }
    public function serialize()
    {
        return serialize(array(
            $this->first,
            $this->second
        ));
    }
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        // add a few extra elements in the array to ensure that we have enough keys when unserializing
        // older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));

        list(
            $this->first,
            $this->second
            ) = $data;
    }
}