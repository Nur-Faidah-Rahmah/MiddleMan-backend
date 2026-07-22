<?php

namespace App\Enums;

enum JobStatus: string
{
    case Draft = 'draft';
    case Open = 'open';
    case Taken = 'taken';
    case Submitted = 'submitted';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}