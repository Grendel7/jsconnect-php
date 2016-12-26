<?php

namespace HansAdema\JsConnect;

/**
 * Class User
 *
 * Data container for user data
 *
 * @package HansAdema\JsConnect
 */
class User
{
    /**
     * @var string A unique ID for the user in your system REQUIRED
     */
    protected $id;

    /**
     * @var string The username REQUIRED
     */
    protected $name;

    /**
     * @var string The e-mail address REQUIRED
     */
    protected $email;

    /**
     * @var string A URL for the profile picture OPTIONAL
     */
    protected $photoUrl;

    /**
     * @var array The list of roles assigned to the user OPTIONAL
     */
    protected $roles = [];

    /**
     * User constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, 'key')) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id ? $this->id : '';
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name ? $this->name : '';
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email ? $this->email : '';
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
        return $this->photoUrl ? $this->photoUrl : '';
    }

    /**
     * @param string $photoUrl
     */
    public function setPhotoUrl($photoUrl)
    {
        $this->photoUrl = $photoUrl;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles ? $this->roles : [];
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    /**
     * Get the response data in Vanilla format
     *
     * @return array
     */
    public function getResponseData()
    {
        $data = [
            'uniqueid' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
        ];

        if ($this->getPhotoUrl()) {
            $data['photourl'] = $this->getPhotoUrl();
        }

        if (!empty($this->getRoles())) {
            $data['roles'] = implode(',', $this->getRoles());
        }

        ksort($data);

        return $data;
    }
}