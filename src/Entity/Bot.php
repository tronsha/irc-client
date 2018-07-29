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
}
