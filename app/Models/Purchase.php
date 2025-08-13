<?php

namespace App\Models;

use App\Traits\FileUpload;
use App\Traits\HashUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Purchase extends Model
{
    use HashUuid, FileUpload, HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id',
        'proof',
        'label'
    ];

    protected $appends = ['proof_url'];

    /**
     * Trait FileUpload
     */
    protected function fileSettings(): void
    {
        $this->settings = [
            'attributes'  => ['jpeg', 'jpg', 'png'],
            'path'        => 'upload/purchase/',
            'softdelete'  => false
        ];
    }

    /**
     * Relation to order
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Accessor proof_url
     */
    public function getProofUrlAttribute(): string | null
    {
        $this->fileSettings();
        return $this->proof
            ? asset($this->settings['path'] . $this->proof)
            : null;
    }

    /**
     * Filtering.
     */
    public function scopeFilter($query, $search)
    {
        $query->when($search, function ($q) use ($search) {
            $search = strtolower($search);

            $q->where(function ($subQuery) use ($search) {
                $subQuery
                    ->whereHas('order.user', function ($q2) use ($search) {
                        $q2->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                            ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
                    })
                    ->orWhereHas('order.tryout', function ($q3) use ($search) {
                        $q3->whereRaw('LOWER(title) LIKE ?', ["%{$search}%"]);
                    });
            });
        });
    }
}
