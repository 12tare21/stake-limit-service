<?php

namespace App\Infrastructure\Repositories\Eloquent;

use App\Infrastructure\Repositories\Interfaces\Tickets;
use App\Infrastructure\Models\Ticket;

class TicketRepository extends MutableRepository implements Tickets{
    public function __construct(Ticket $model){
        parent::__construct($model);
    }
}