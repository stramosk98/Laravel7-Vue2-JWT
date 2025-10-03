<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public const PENDING_STATUS = 1;
    public const IN_TRANSIT_STATUS = 2;
    public const DELIVERED_STATUS = 3;

    protected $fillable = [
        'client_id', 'address', 'status',
    ];

    protected $casts = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Scope a query to filter orders.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters)
    {
        return $query->when($filters['status'] ?? null, function ($q, $status) {
            $q->where('status', $status);
        })->when($filters['client'] ?? null, function ($q, $client) {
            $q->where('client_id', $client);
        });
    }
}
