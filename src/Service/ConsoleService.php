<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Formatter\ConsoleFormatterService;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class ConsoleService
{
    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * @var FormatterConsole
     */
    private $formatter;

    /**
     * ConsoleService constructor.
     * @param ConsoleFormatterService $consoleFormatterService
     */
    public function __construct(ConsoleFormatterService $consoleFormatterService)
    {
        $this->setOutput(new ConsoleOutput());
        $this->formatter = $consoleFormatterService;
    }

    /**
     * @param ConsoleOutput $output
     * @return $this
     */
    public function setOutput(ConsoleOutput $output)
    {
        $output->getFormatter()->setStyle('timestamp', new OutputFormatterStyle('yellow'));
        $output->getFormatter()->setStyle('input', new OutputFormatterStyle('cyan'));
        $output->getFormatter()->setStyle('output', new OutputFormatterStyle('magenta'));
        $this->consoleOutput = $output;

        return $this;
    }

    /**
     * @param string $text
     * @throws \Exception
     */
    public function writeToConsole(string $text)
    {
        $this->consoleOutput->writeln($text);
    }

    /**
     * @param string $text
     * @return string
     */
    public function escape(string $text): string
    {
        return OutputFormatter::escape($text);
    }

    /**
     * @param string $text
     * @param bool $escape
     * @param null $length
     * @param bool $break
     * @param bool $wordwrap
     * @param int $offset
     * @return string
     * @throws \Exception
     */
    public function prepare(
        string $text,
        bool $escape = true,
        $length = null,
        bool $break = true,
        bool $wordwrap = true,
        int $offset = 0
    ): string {
        $text = $this->formatter->bold($text);
        $text = $this->formatter->underline($text);
        $text = $this->formatter->color($text);
        if (false !== $length) {
            if (null === $length) {
                $length = $this->getColumns();
            }
            if ($length > $offset) {
                $text = $this->build($text, $length, $break, $wordwrap, $offset);
            }
        }
        $text .= ('\\' === mb_substr($text, -1)) ? ' ' : '';

        return true === $escape ? $this->escape($text) : $text;
    }

    /**
     * @param string $char
     * @param int $count
     * @param bool $ignore
     * @return string
     */
    public function count(string $char, int &$count, bool &$ignore): string
    {
        if ("\033" === $char) {
            $ignore = true;
        }
        if (!$ignore) {
            $count++;
        }
        if ($ignore && 'm' === $char) {
            $ignore = false;
        }

        return $char;
    }

    /**
     * @param string $text
     * @param int $length
     * @param bool $break
     * @param bool $wordwrap
     * @param int $offset
     * @return string
     * @throws \Exception
     */
    protected function build(string $text, int $length, bool $break, bool $wordwrap, int $offset): string
    {
        $length -= $offset;
        if ($this->len($text) > $length) {
            if (true === $break) {
                if (true === $wordwrap) {
                    $text = $this->wordwrap($text, $length);
                } else {
                    $text = $this->split($text, $length, PHP_EOL);
                }
                $text = str_replace(PHP_EOL, PHP_EOL . str_repeat(' ', $offset), $text);
            } else {
                $text = $this->cut($text, $length - 3) . '...';
                if (false !== mb_strpos($text, "\033")) {
                    $text .= "\033[0m";
                }
            }
        }

        return $text;
    }

    /**
     * @param string $text
     * @return int
     */
    protected function len(string $text): int
    {
        return mb_strlen(preg_replace("/\033\[[0-9;]+m/", '', $text));
    }

    /**
     * @param string $text
     * @param int $length
     * @param string $break
     * @param bool $cut
     * @throws \Exception
     * @return string
     */
    protected function wordwrap(string $text, int $length = 80, string $break = PHP_EOL, bool $cut = true): string
    {
        if ($length < 1) {
            throw new \Exception('Length cannot be negative or null.');
        }
        $textArray = explode(' ', $text);
        $count = 0;
        $lineCount = 0;
        $output = [];
        $output[$lineCount] = '';
        foreach ($textArray as $word) {
            $wordLength = $this->len($word);
            if (($count + $wordLength) <= $length) {
                $count += $wordLength + 1;
                $output[$lineCount] .= $word . ' ';
            } elseif ($cut && $wordLength > $length) {
                $wordArray = explode(' ', $this->split($word, $length, ' '));
                foreach ($wordArray as $word) {
                    $wordLength = $this->len($word);
                    $output[$lineCount] = trim($output[$lineCount]);
                    $lineCount++;
                    $count = $wordLength + 1;
                    $output[$lineCount] = $word . ' ';
                }
            } else {
                $output[$lineCount] = trim($output[$lineCount]);
                $lineCount++;
                $count = $wordLength + 1;
                $output[$lineCount] = $word . ' ';
            }
        }

        return trim(implode($break, $output));
    }

    /**
     * @param string $text
     * @param int $length
     * @param string $end
     * @throws \Exception
     * @return string
     */
    protected function split(string $text, int $length = 80, string $end = PHP_EOL): string
    {
        if ($length < 1) {
            throw new \Exception('Length cannot be negative or null.');
        }
        $output = '';
        $count = 0;
        $ignore = false;
        $len = mb_strlen($text);
        $textArray = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < $len; $i++) {
            $output .= $this->count($textArray[$i], $count, $ignore);
            if ($count === $length) {
                $count = 0;
                $output .= $end;
            }
        }

        return $output;
    }

    /**
     * @param string $text
     * @param int $length
     * @throws \Exception
     * @return string
     */
    protected function cut(string $text, int $length): string
    {
        if ($length < 1) {
            throw new \Exception('Length cannot be negative or null.');
        }
        $output = '';
        $count = 0;
        $ignore = false;
        $len = mb_strlen($text);
        $textArray = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
        for ($i = 0; $i < $len; $i++) {
            $output .= $this->count($textArray[$i], $count, $ignore);
            if ($count === $length) {
                break;
            }
        }

        return $output;
    }

    /**
     * @return int
     */
    protected function getColumns(): int
    {
        $matches = [];
        preg_match('/columns\s([0-9]+);/', strtolower(exec('stty -a | grep columns')), $matches);

        return (false === isset($matches[1]) || intval($matches[1]) <= 0) ? 0 : intval($matches[1]);
    }
}
