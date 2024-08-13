<?php

namespace App\Domains\RevenueStreamTypes\Enums;

use BenSampo\Enum\Enum;

final class RevenueStreamTypesEnum extends Enum
{
    const SINGLE_LINE_STRING = 'single_line_string';
    const SINGLE_LINE_NUMBER = 'single_line_number';
    const SINGLE_LINE_DATE = 'single_line_date';
    const SINGLE_LINE_TIME = 'single_line_time';
    const SINGLE_LINE_DATETIME = 'single_line_datetime';
    const SINGLE_LINE_ANY = 'single_line_string';
    const MULTI_LINE_STRING = 'multi_line_string';
    const MULTI_LINE_NUMBER = 'multi_line_number';
    const MULTI_LINE_DATE = 'multi_line_date';
    const MULTI_LINE_TIME = 'multi_line_time';
    const MULTI_LINE_DATETIME = 'multi_line_datetime';
    const MULTI_LINE_ANY = 'multi_line_string';
    const TEXT = 'text';
    const RANGE = 'range';
    const RADIO = 'radio';
    const CHECKBOX = 'checkbox';
    const DROPBOX = 'dropbox';
    const REPEATER = 'repeater';

}
