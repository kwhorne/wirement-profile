<?php

namespace Wirement\Profile\Models;

use Wirement\Profile\WirementProfile;
use Illuminate\Database\Eloquent\Model;

class TeamInvitation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the team that the invitation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        $model = WirementProfile::plugin()->teamModel();

        $foreignKey = WirementProfile::getForeignKeyColumn($model);

        return $this->belongsTo($model, $foreignKey);
    }
}
