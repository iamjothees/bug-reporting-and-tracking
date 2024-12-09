<?php

namespace App;

enum UserRole: string
{
    case REPORTER = 'reporter';
    case DEVELOPER = 'developer';
    case ADMIN = 'admin';
}
