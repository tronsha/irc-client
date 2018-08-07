<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bot.
 *
 * @ORM\Table(name="bot")
 * @ORM\Entity
 */
class Bot
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
     * @ORM\Column(name="pid", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $pid;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nick", type="string", length=255, nullable=true)
     */
    private $nick;

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
     * @return Bot
     */
    public function setId(int $id): Bot
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     *
     * @return Bot
     */
    public function setPid(int $pid): Bot
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getNick(): ?string
    {
        return $this->nick;
    }

    /**
     * @param null|string $nick
     *
     * @return Bot
     */
    public function setNick(?string $nick): Bot
    {
        $this->nick = $nick;

        return $this;
    }
}
