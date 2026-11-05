<?php

namespace App\Enums;

enum LearningType: string
{
    case SELF_PACED = 'self_paced';
    case INSTRUCTOR_LED = 'instructor_led';
    case BLENDED = 'blended';

    public function label(): string
    {
        return match ($this) {
            self::SELF_PACED => 'Self-Paced',
            self::INSTRUCTOR_LED => 'Instructor-Led',
            self::BLENDED => 'Blended Learning',
        };
    }
}
