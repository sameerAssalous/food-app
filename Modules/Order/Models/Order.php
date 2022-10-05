<?php
declare(strict_types=1);

namespace Module\Order\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UuidTrait;

    const NEW_STATUS = 1;
    const IN_PROGRESS_STATUS = 2;
    const DELIVERED_STATUS = 3;
    const CANCELED_STATUS = 4;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'status',
    ];

    protected $casts = [
        'id' => 'string',
        'status' => 'boolean',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products')->withPivot('quantity')
            ->using(new class extends Pivot {
                use UuidTrait;
            });
    }
}
