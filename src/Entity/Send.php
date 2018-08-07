<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Send.
 *
 * @ORM\Table(name="send")
 * @ORM\Entity(repositoryClass="\App\Repository\SendRepository")
 */
class Send
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
     * @ORM\Column(name="bot_id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $botId;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", length=0, nullable=false)
     */
    private $text;

    /**
     * @var int
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
     *
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
     *
     * @return Send
     */
    public function setBotId(int $botId): Send
    {
        $this->botId = $botId;

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
     * @return Send
     */
    public function setText(string $text): Send
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return Send
     */
    public function setPriority(int $priority): Send
    {
        $this->priority = $priority;

        return $this;
    }
}
