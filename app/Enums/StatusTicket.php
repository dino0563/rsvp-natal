<?php
namespace App\Enums;

enum StatusTicket: string {
    case PENDING = 'pending';
    case GENERATED = 'generated';
    case SENT = 'sent';
    case USED = 'used';
    case REVOKED = 'revoked';
}
