<?php

namespace App\Services;

use App\Models\Module;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ModuleService
{
    protected static $keyCache = 'hallpos_modules';

    public static function isEnabled(string $module): bool
    {
        $modules = self::getModules();
        $isEnabled = $modules->filter(function ($item) use ($module) {
            return strcasecmp($item['name'], $module) === 0;
        })
            ->first()['is_enabled'] ?? false;

        return $isEnabled;
    }

    public static function exists(string $module): bool
    {
        $modules = self::getModules();

        $isExists = $modules->filter(function ($item) use ($module) {
            return strcasecmp($item['name'], $module) === 0;
        })
            ->where('is_enabled', true)
            ->count() > 0;

        return $isExists;
    }

    protected static function getModules(): Collection
    {
        if (Cache::has(self::$keyCache)) {
            $modules = Cache::get(self::$keyCache);

            return collect($modules);
        } else {
            $modules = Module::all()->toArray();
            Cache::forever(self::$keyCache, $modules);

            return collect($modules);
        }
    }

    public static function refreshCache()
    {
        Cache::forget(self::$keyCache);
    }
}
