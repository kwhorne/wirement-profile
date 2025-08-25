<?php

namespace Wirement\Profile\Models;

use Wirement\Profile\WirementProfile;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Membership extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The table associated with the pivot model.
     *
     * @var string
     */
    protected $table = 'team_user';

    public function user(): BelongsTo
    {
        $model = WirementProfile::plugin()->userModel();

        $foreignKey = WirementProfile::getForeignKeyColumn($model);

        return $this->belongsTo($model, $foreignKey);
    }

    public function team(): BelongsTo
    {
        $model = WirementProfile::plugin()->teamModel();

        $foreignKey = WirementProfile::getForeignKeyColumn($model);

        return $this->belongsTo($model, $foreignKey);
    }
}
