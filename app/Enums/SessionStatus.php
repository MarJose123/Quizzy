<?php

namespace App\Enums;

enum SessionStatus: string
{
    case Waiting = 'waiting';
    case Active = 'active';
    case Finished = 'finished';
}
