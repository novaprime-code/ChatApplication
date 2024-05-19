<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VideoCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['initiator_id', 'recipient_id', 'status', 'start_time', 'end_time'];

    public function initiator()
    {
        return $this->belongsTo(User::class, 'initiator_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
