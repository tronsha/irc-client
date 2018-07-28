<?php

declare(strict_types=1);

namespace App\Service\Irc;

class NetworkService
{
    private $host;
    private $port;
    private $password;

    /**
     * @param string      $server
     * @param int         $port
     * @param string|null $password
     */
    public function setIrcServer(string $server, int $port, string $password = null)
    {
        $this->host = $server;
        $this->port = $port;
        $this->password = $password;
    }

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        if (null !== $arguments['host']) {
            $this->host = $arguments['host'];
        }
        if (null !== $arguments['port']) {
            $this->port = (int) $arguments['port'];
        }
        if (null !== $arguments['password']) {
            $this->password = $arguments['password'];
        }
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return [
            'host' => $this->host,
            'port' => $this->port,
            'password' => $this->password,
        ];
    }

    /**
     * @return string
     */
    public function getNetwork(): string
    {
        preg_match('/([^\.]+)\.[a-z]{2}[^\.]*$/', $this->host, $matches);

        return $matches[1];
    }

    /**
     * @return string
     */
    public function getIrcServerHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getIrcServerPort(): int
    {
        return $this->port;
    }

    /**
     * @return null|string
     */
    public function getIrcServerPassword(): ?string
    {
        return $this->password;
    }
}
