<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function (Model $model) {
            static::logActivity($model, 'created');
        });

        static::updated(function (Model $model) {
            static::logActivity($model, 'updated');
        });

        static::deleted(function (Model $model) {
            static::logActivity($model, 'deleted');
        });
    }

    protected static function logActivity(Model $model, string $action)
    {
        try {
            $oldProps = null;
            $newProps = null;

            if ($action === 'created') {
                $newProps = $model->getAttributes();
                $newProps = static::filterSensitiveFields($newProps);
            } elseif ($action === 'deleted') {
                $oldProps = $model->getAttributes();
                $oldProps = static::filterSensitiveFields($oldProps);
            } elseif ($action === 'updated') {
                $changed = $model->getChanges();
                if (empty($changed)) {
                    return;
                }
                
                $old = [];
                $new = [];
                foreach ($changed as $key => $newValue) {
                    if ($key === 'updated_at') continue;
                    $old[$key] = $model->getOriginal($key);
                    $new[$key] = $newValue;
                }

                if (empty($new)) {
                    return;
                }

                $oldProps = static::filterSensitiveFields($old);
                $newProps = static::filterSensitiveFields($new);
            }

            $description = static::getActivityDescription($model, $action);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'loggable_type' => get_class($model),
                'loggable_id' => $model->getKey(),
                'description' => $description,
                'old_properties' => $oldProps,
                'new_properties' => $newProps,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            logger()->error("Activity Logging failed: " . $e->getMessage());
        }
    }

    protected static function filterSensitiveFields(array $properties): array
    {
        $sensitive = ['password', 'remember_token', 'verification_code', 'email_verification_code', 'token'];
        foreach ($sensitive as $field) {
            if (array_key_exists($field, $properties)) {
                $properties[$field] = '[REDACTED]';
            }
        }
        return $properties;
    }

    protected static function getActivityDescription(Model $model, string $action): string
    {
        $className = class_basename($model);
        
        $identifier = '';
        if (isset($model->title)) {
            $identifier = "'{$model->title}'";
        } elseif (isset($model->name)) {
            $identifier = "'{$model->name}'";
        } elseif (isset($model->slug)) {
            $identifier = "'{$model->slug}'";
        } elseif (isset($model->email)) {
            $identifier = "'{$model->email}'";
        } else {
            $identifier = "#" . $model->getKey();
        }

        return ucfirst($action) . " {$className} {$identifier}";
    }
}
