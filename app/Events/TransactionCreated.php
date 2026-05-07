<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('transactions'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'transaction.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->transaction->id,
            'total'      => $this->transaction->total,
            'paid'       => $this->transaction->paid,
            'change'     => $this->transaction->change,
            'status'     => $this->transaction->status,
            'created_at' => $this->transaction->created_at->toIso8601String(),
            'items_count' => $this->transaction->items()->count(),
        ];
    }
}
