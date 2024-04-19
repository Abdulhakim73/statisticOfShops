<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BranchImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'branch_id',
        'path',
        'mime_type'
    ];

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

}
