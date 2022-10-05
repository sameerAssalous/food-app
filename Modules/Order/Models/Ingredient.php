<?php
declare(strict_types=1);

namespace Module\Order\Models;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UuidTrait;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'quantity',
        'standard_quantity',
    ];

    protected $casts = [
        'id' => 'string',
        'quantity' => 'integer',
        'standard_quantity' => 'integer',
    ];

}
