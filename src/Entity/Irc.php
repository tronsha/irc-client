<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Irc.
 *
 * @ORM\Table(name="irc")
 * @ORM\Entity
 */
class Irc
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="bot_id", type="integer", nullable=false)
     */
    private $botId;

    /**
     * @var int
     *
     * @ORM\Column(name="direction", type="integer", nullable=false)
     */
    private $direction;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime", length=255, nullable=false)
     */
    private $time;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Irc
     */
    public function setId(int $id): Irc
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getBotId(): int
    {
        return $this->botId;
    }

    /**
     * @param int $botId
     *
     * @return Irc
     */
    public function setBotId(int $botId): Irc
    {
        $this->botId = $botId;

        return $this;
    }

    /**
     * @return int
     */
    public function getDirection(): int
    {
        return $this->direction;
    }

    /**
     * @param int $direction
     *
     * @return Irc
     */
    public function setDirection(int $direction): Irc
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return Irc
     */
    public function setText(string $text): Irc
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     *
     * @return Irc
     */
    public function setTime(\DateTime $time): Irc
    {
        $this->time = $time;

        return $this;
    }
}
