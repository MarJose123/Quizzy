<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Host = 'host';
    case Participant = 'participant';
    case Viewer = 'viewer';
}
