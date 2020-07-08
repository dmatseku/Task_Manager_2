<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Task extends Model
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'tasks';

    /**
     * Fillable columns
     *
     * @var array
     */
    protected $fillable = ['name', 'begin_in', 'finish_in', 'status', 'type', 'description'];

    /**
     * Standard values
     *
     * @var array
     */
    protected $attributes = [
        'name' => 'Task Name',
        'status' => 1,
        'type' => 'other',
        'description' => ''
    ];

    /**
     * Possible statuses id's
     *
     * @var array
     */
    private static $statuses_id = [
        'created' => 1,
        'began' => 2,
        'finished' => 3
    ];

    /**
     * Possible statuses
     *
     * @var array
     */
    private static $statuses_names = [
        1 => 'created',
        2 => 'began',
        3 => 'finished'
    ];

    /**
     * array of types
     *
     * @var array
     */
    private static $types = [
        1 => 'Development',
        2 => 'Bug fix',
        3 => 'Design',
        4 => 'Other'
    ];

    /**
     * get array of types
     *
     * @return array|string[]
     */
    public static function getTypesArray() {
        return self::$types;
    }

    /**
     * Accessor for status
     *
     * @param $value
     *
     * @return int
     */
    public function getStatusAttribute($value) {
        if (!is_numeric($value)) {
            return self::$statuses_id[$value];
        }
        return $value;
    }

    /**
     * Mutator for status
     *
     * @param $value
     */
    public function setStatusAttribute($value) {
        if (!is_numeric($value)) {
            $this->attributes['status'] = self::$statuses_id[$value];
        } else {
            $this->attributes['status'] = $value;
        }
    }

    /**
     * Relation to User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
