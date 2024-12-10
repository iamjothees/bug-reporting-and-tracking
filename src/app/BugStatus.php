<?php

namespace App;

enum BugStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::IN_PROGRESS => 'In Progress',
            default => str($this->value)->title()
        };
    }
}
