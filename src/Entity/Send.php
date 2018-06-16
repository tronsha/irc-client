<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DashboardTile.
 *
 * @ORM\Entity(repositoryClass="\App\Repository\SendRepository")
 * @ORM\Table(name="send")
 */
class Send
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
     * @ORM\Column(name="bot_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $botId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="text", type="text", nullable=false)
     */
    private $text;

    /**
     * @var bool
     *
     * @ORM\Column(name="priority", type="integer", nullable=false)
     */
    private $priority;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Send
     */
    public function setId(int $id): Send
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
     * @return Send
     */
    public function setBotId(int $botId): Send
    {
        $this->botId = $botId;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param null|string $text
     * @return Send
     */
    public function setText(?string $text): Send
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPriority(): bool
    {
        return $this->priority;
    }

    /**
     * @param bool $priority
     * @return Send
     */
    public function setPriority(bool $priority): Send
    {
        $this->priority = $priority;

        return $this;
    }
}
