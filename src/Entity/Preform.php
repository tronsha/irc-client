<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Preform.
 *
 * @ORM\Entity(repositoryClass="\App\Repository\PreformRepository")
 * @ORM\Table(name="preform")
 */
class Preform
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
     * @var string
     *
     * @ORM\Column(name="network", type="text", nullable=false)
     */
    private $network;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=false)
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
     * @return Preform
     */
    public function setId(int $id): Preform
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getNetwork(): string
    {
        return $this->network;
    }

    /**
     * @param string $network
     *
     * @return Preform
     */
    public function setNetwork(string $network): Preform
    {
        $this->network = $network;

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
     * @return Preform
     */
    public function setText(?string $text): Preform
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
     * @return Preform
     */
    public function setPriority(int $priority): Preform
    {
        $this->priority = $priority;

        return $this;
    }
}
