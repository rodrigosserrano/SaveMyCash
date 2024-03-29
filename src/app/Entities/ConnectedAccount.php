<?php

namespace App\Entities;

use App\Illuminate\Custom\Database\Eloquent\Concerns\HasUuid;
use App\Illuminate\Custom\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ConnectedAccount extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'provider',
        'provider_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'connected_account_id');
    }
}
