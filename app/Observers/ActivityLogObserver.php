<?php

namespace App\Observers;

use Spatie\Activitylog\Models\Activity;

class ActivityLogObserver
{
    public function creating(Activity $activity)
    {
        // اضافه کردن IP و UserAgent به properties
        $properties = $activity->properties ?? collect();
        
        if (!isset($properties['ip_address'])) {
            $properties['ip_address'] = request()->ip() ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        }
        
        if (!isset($properties['user_agent'])) {
            $properties['user_agent'] = request()->userAgent() ?? $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
        }
        
        $activity->properties = $properties;
    }
}