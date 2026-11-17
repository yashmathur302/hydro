<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A single site-wide setting stored as a key/value pair.
 * Read and write settings through App\Services\SiteSettingService, which
 * caches them and validates keys against App\Enums\SiteSettingKey.
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 */
class SiteSetting extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'value',
    ];
}
