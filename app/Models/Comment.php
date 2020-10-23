<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/22/2018
 * Time: 6:21 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Comment extends \Moloquent
{
    use HasTimestamps;
    protected $connection = 'mongodb';
    protected $collection = 'comments';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        '_id', 'name', 'createdAt', 'username', 'fullname', 'token', 'status', 'created_at', 'updated_at'
    ];
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at'];
}