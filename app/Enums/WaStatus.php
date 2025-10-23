<?php
namespace App\Enums;

enum WaStatus: string {
    case QUEUED = 'queued';
    case SENT = 'sent';
    case DELIVERED = 'delivered';
    case READ = 'read';
    case FAILED = 'failed';
    case BLOCKED = 'blocked';
    case UNKNOWN = 'unknown';
}
