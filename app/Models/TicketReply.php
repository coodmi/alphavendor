<?php

namespace App\Models;

// Alias for TicketMessage to maintain compatibility
class TicketReply extends TicketMessage
{
    protected $table = 'ticket_messages';
}
