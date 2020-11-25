<?php

namespace App\Infrastructure\Repositories\Queries;

use App\Infrastructure\Models\Ticket;
use App\Infrastructure\Repositories\Interfaces\Tickets;
use Illuminate\Support\Facades\DB;

class TicketQuery implements Tickets{
    protected $ticketDB;

    public function __construct(Ticket $ticket)
    {
        $this->ticketDB = DB::table($ticket->getTable());
    }

    public function all()
    {
        return $this->ticketDB->all();
    }
    
    public function find($id)
    {
        return $this->ticketDB->find($id);
    }
    
    public function findByAttribute($attr, $value)
    {
        return $this->ticketDB->where($attr, $value)->get();
    }
    
    public function create(array $data)
    {
        return $this->ticketDB->insert($data);
    }
    
    public function update($id, array $data)
    {
        return $this->ticketDB->where('id', $id)->update($data);
    }
    
    public function delete($id)
    {
        return $this->ticketDB->where('id', $id)->delete();
    }
    
    public function findOrCreate($id, array $data = [])
    {
        try{
            return $this->ticketDB->find($id);
        } catch(\Exception $e){
            return $this->ticketDB->insert($data);
        }
    }
    
    public function sumByAttribute($propName, $attr, $value)
    {
        return $this->ticketDB->where($attr, $value)->sum($propName);
    }
}
    