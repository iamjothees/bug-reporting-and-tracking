<?php

namespace App;

enum BugSeverity: string
{
    Case LOW = 'low';
    Case MEDIUM = 'medium';
    Case HIGH = 'high';
    Case CRITICAL = 'critical';
}
