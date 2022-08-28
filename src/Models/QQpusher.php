<?php

declare (strict_types=1);
namespace App\Plugins\QQPusher\src\Models;

use App\Model\Model;

/**
 * @property string $topic_id 
 */
class QQpusher extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'qqpusher';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['topic_id'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    public $timestamps = false;
}