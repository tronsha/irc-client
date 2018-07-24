<?php

declare(strict_types=1);

namespace App\Service\Formatter;

abstract class AbstractFormatter
{
    /**
     * @var string|null
     */
    protected $type = null;

    /**
     * @param string $text
     * @return string
     */
    public function color(string $text): string
    {
        $coloredText = '';
        $colorType = $fontColor = $backgroundColor = '';
        $reset = false;
        foreach (str_split($text) as $char) {
            if ("\x03" === $char) {
                $colorType = 'font';
            } elseif ('font' === $colorType && (0 === strlen($fontColor) || 1 === strlen($fontColor)) && 48 <= ord(
                    $char
                ) && 57 >= ord($char)) {
                $fontColor .= $char;
            } elseif ('font' === $colorType && (1 === strlen($fontColor) || 2 === strlen(
                        $fontColor
                    )) && ',' === $char) {
                $colorType = 'background';
            } elseif ('background' === $colorType && (0 === strlen($backgroundColor) || 1 === strlen(
                        $backgroundColor
                    )) && 48 <= ord($char) && 57 >= ord($char)) {
                $backgroundColor .= $char;
            } elseif ('font' === $colorType || 'background' === $colorType) {
                if ('' !== $backgroundColor) {
                    $coloredText .= $this->getColor(intval($fontColor), intval($backgroundColor));
                    $reset = true;
                } elseif ('' !== $fontColor) {
                    $coloredText .= $this->getColor(intval($fontColor));
                    $reset = true;
                } else {
                    $coloredText .= $this->getColor();
                    $reset = false;
                }
                if ('background' === $colorType && '' === $backgroundColor) {
                    $coloredText .= ',';
                }
                $colorType = $fontColor = $backgroundColor = '';
                $coloredText .= $char;
            } else {
                $coloredText .= $char;
            }
        }
        if ($reset) {
            $coloredText .= $this->getColor();
        }

        return $coloredText;
    }

    abstract protected function matchColor(int $id): string;

    abstract protected function getColor(int $fontColor = null, int $backgroundColor = null): string;

    abstract protected function bold(string $text): string;

    abstract protected function underline(string $text): string;

    /**
     * @param string $text
     * @param string $delimiter
     * @param string $start
     * @param string $stop
     * @return string
     */
    protected function format(string $text, string $delimiter, string $start = null, string $stop = null): string
    {
        $formatArray = explode($delimiter, $text);
        $text = array_shift($formatArray);
        $open = false;
        foreach ($formatArray as $part) {
            if (!$open) {
                $text .= $start;
                $open = true;
            } else {
                $text .= $stop;
                $open = false;
            }
            $text .= $part;
        }
        if ($open) {
            $text .= $stop;
        }

        return $text;
    }
}