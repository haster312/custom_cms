<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaseModel extends Model
{
    public function setCreatedAtAttribute($value)
    {
        $value = $value->timestamp;
        $this->attributes['created_at'] = $value;
    }

    public function setUpdatedAtAttribute($value)
    {
        $value = $value->timestamp;
        $this->attributes['updated_at'] = $value;
    }

    public function setDeletedAtAttribute($value)
    {
        $value = $value->timestamp;
        $this->attributes['deleted_at'] = $value;
    }

    public function getCreatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }
}
