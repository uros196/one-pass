<?php

namespace App\Enums;

enum CardColors: int
{
    case BLUE = 1;
    CASE GRAY = 2;
    case PURPLE = 3;
    case PINK = 4;
    case RED = 5;
    case BROWN = 6;
    case GREEN = 7;
    case ORANGE = 8;
    case YELLOW = 9;

    /**
     * Get the background color based on the selected color.
     *
     * @return string
     */
    public function backgroundColor(): string
    {
        // TODO: implement other colors

        return match ($this) {
            default => 'rgb(91, 159, 217)'
        };
    }

    /**
     * Get the text color based on selected color.
     *
     * @return string
     */
    public function fontColor(): string
    {
        // TODO: implement other colors

        return match ($this) {
            default => 'white'
        };
    }

    /**
     * Get color label.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::BLUE => 'Blue',
            self::GRAY => 'Gray',
            self::PURPLE => 'Purple',
            self::PINK => 'Pink',
            self::RED => 'Red',
            self::BROWN => 'Brown',
            self::GREEN => 'Green',
            self::ORANGE => 'Orange',
            self::YELLOW => 'Yellow',
        };
    }

    /**
     * Get an array of enum values and their corresponding labels.
     *
     * @return array
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
