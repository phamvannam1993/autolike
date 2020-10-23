<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/22/2018
 * Time: 6:21 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Transaction extends \Moloquent
{
    use HasTimestamps;
    protected $connection = 'mongodb';
    protected $collection = 'transactions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        '_id', 'code', 'value', 'date', 'cron_check', 'status', 'bonus', 'createdAt', 'username', 'fullname', 'token'
    ];
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at'];
}