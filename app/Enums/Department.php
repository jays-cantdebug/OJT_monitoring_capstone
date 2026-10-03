<?php

namespace App\Enums;

enum Department: string
{
    case EDUC = 'EDUC';
    case CRIM = 'CRIM';
    case BSBA = 'BSBA';
    case HM = 'HM';
    case IT = 'IT';

    /**
     * Official college name, for institutional contexts
     * (e.g. "College of Information Technology").
     */
    public function label(): string
    {
        return match ($this) {
            self::EDUC => 'College of Education, Arts and Sciences',
            self::CRIM => 'College of Criminal Justice Education',
            self::BSBA => 'College of Business Administration',
            self::HM => 'College of Hospitality Management',
            self::IT => 'College of Information Technology',
        };
    }

    /**
     * Full degree/program name, for student-facing contexts
     * (e.g. "BS Information Technology").
     */
    public function programLabel(): string
    {
        return match ($this) {
            self::EDUC => 'Bachelor of Education',
            self::CRIM => 'BS Criminology',
            self::BSBA => 'BS Business Administration',
            self::HM => 'BS Hospitality Management',
            self::IT => 'BS Information Technology',
        };
    }
}
