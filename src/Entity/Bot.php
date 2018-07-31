<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Bot.
 *
 * @ORM\Table(name="bot")
 */
class Bot
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(name="id", type="integer", nullable=false)
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="pid", type="integer", nullable=false)
     */
    private $pid;

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
}
