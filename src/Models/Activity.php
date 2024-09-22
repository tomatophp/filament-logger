<?php

namespace TomatoPHP\FilamentLogger\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property string $request_hash
 * @property string $http_version
 * @property float $response_time
 * @property integer $status
 * @property string $method
 * @property string $url
 * @property string $referer
 * @property mixed $query
 * @property string $remote_address
 * @property string $user_agent
 * @property mixed $response
 * @property string $level
 * @property string $user
 * @property mixed $log
 * @property string $created_at
 * @property string $updated_at
 */
class Activity extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'request_hash', 'http_version', 'response_time', 'status', 'method', 'url', 'referer', 'query', 'remote_address', 'user_agent', 'response', 'level', 'user', 'log', 'created_at', 'updated_at'];

    protected $casts = [
        'query' => 'json',
        'response' => 'json',
        'log' => 'json',
    ];

    public function model()
    {
        return $this->morphTo('model');
    }
}
