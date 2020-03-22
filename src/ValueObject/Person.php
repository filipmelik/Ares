<?php

namespace Defr\ValueObject;

use DateTime;
use DateTimeInterface;

final class Person implements \JsonSerializable
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTimeInterface
     */
    private $birthday;

    /**
     * @var string
     */
    private $address;

    /**
     * @var DateTimeInterface
     */
    private $registered;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $name
     * @param DateTimeInterface $birthday
     * @param string $address
     * @param DateTimeInterface $registered
     * @param $type
     */
    public function __construct(
        $name = null,
        DateTimeInterface $birthday = null,
        $address = null,
        DateTimeInterface $registered = null,
        $type = null
    )
    {
        $this->name = $name;
        $this->birthday = $birthday;
        $this->address = $address;
        $this->registered = $registered;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return DateTime|DateTimeInterface
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return DateTime|DateTimeInterface
     */
    public function getRegistered()
    {
        return $this->registered;
    }
    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'name'       => $this->name,
            'birthday'   => $this->birthday,
            'address'    => $this->address,
            'registered' => $this->registered,
            'type'       => $this->type,
        ];
    }
}
