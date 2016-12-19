<?php
namespace CustomizedHwi\HwiBundle\Traits;
trait SocialRegistrationUserTrait{
    /**
     * @ORM\Column(name="facebook_id", type="string", length=255, nullable=true)
     */
    protected $facebook_id;
    /**
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    protected $facebook_access_token;
    /**
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     */
    protected $google_id;
    /**
     * @ORM\Column(name="google_access_token", type="string", length=255, nullable=true)
     */
    protected $google_access_token;
    /**
     * @ORM\Column(name="vk_id", type="string", length=255, nullable=true)
     */
    protected $vk_id;
    /**
     * @ORM\Column(name="vk_access_token", type="string", length=255, nullable=true)
     */
    protected $vk_access_token;
    /**
     * is using for store vendor name while registration process
     *
     * @var null
     */
    protected $socialVendor = null;
    public function setSocialVendor($vendor)
    {
        $this->vendor = $vendor;
        return $this;
    }
    public function getSocialVendor(){
        return $this->vendor;
    }
    /**
     * Set facebookId
     *
     * @param string $facebookId
     *
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebookAccessToken
     *
     * @param string $facebookAccessToken
     *
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Set googleId
     *
     * @param string $googleId
     *
     * @return User
     */
    public function setGoogleId($googleId)
    {
        $this->google_id = $googleId;

        return $this;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Set googleAccessToken
     *
     * @param string $googleAccessToken
     *
     * @return User
     */
    public function setGoogleAccessToken($googleAccessToken)
    {
        $this->google_access_token = $googleAccessToken;

        return $this;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set vkId
     *
     * @param string $vkId
     *
     * @return User
     */
    public function setVkontakteId($vkId)
    {
        $this->vk_id = $vkId;

        return $this;
    }

    /**
     * Get vkId
     *
     * @return string
     */
    public function getVkontakteId()
    {
        return $this->vk_id;
    }

    /**
     * Set vkAccessToken
     *
     * @param string $vkAccessToken
     *
     * @return User
     */
    public function setVkontakteAccessToken($vkAccessToken)
    {
        $this->vk_access_token = $vkAccessToken;

        return $this;
    }

    /**
     * Get vkAccessToken
     *
     * @return string
     */
    public function getVkontakteAccessToken()
    {
        return $this->vk_access_token;
    }
    /**
     * Set vkId
     *
     * @param string $vkId
     *
     * @return User
     */
    public function setVkId($vkId)
    {
        $this->setVkontakteId($vkId);
    }

    /**
     * Get vkId
     *
     * @return string
     */
    public function getVkId()
    {
        $this->getVkontakteId();
    }

    /**
     * Set vkAccessToken
     *
     * @param string $vkAccessToken
     *
     * @return User
     */
    public function setVkAccessToken($vkAccessToken)
    {
        $this->setVkontakteAccessToken($vkAccessToken);
    }

    /**
     * Get vkAccessToken
     *
     * @return string
     */
    public function getVkAccessToken()
    {
        $this->getVkontakteAccessToken();
    }

}