<?php

namespace App\Enums;

enum DigimonType: string
{
    case FREE = 'free';
    case DATA = 'data';
    case VACCINE = 'vaccine';
    case VIRUS = 'virus';
}
