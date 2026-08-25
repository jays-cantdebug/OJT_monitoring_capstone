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
     * Full name of the department/field, for institutional contexts
     * (e.g. "Information Technology Department").
     */
    public function label(): string
    {
        return match ($this) {
            self::EDUC => 'Education',
            self::CRIM => 'Criminology',
            self::BSBA => 'Business Administration',
            self::HM => 'Hospitality Management',
            self::IT => 'Information Technology',
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
