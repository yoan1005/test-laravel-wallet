<?php

namespace App\Models;

use App\Models\Wallet;
use App\Models\TransferExecuted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringTransfer extends Model
{
    protected $table = 'recurring_transfers';

    protected $fillable = [
        'source_id',
        'target_id',
        'amount',
        'interval',
        'start_date',
        'end_date',
        'reason'
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'source_id');
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo(Wallet::class, 'target_id');
    }

    public function executed(): HasMany
    {
        return $this->hasMany(TransferExecuted::class, 'transfer_id');
    }
}
