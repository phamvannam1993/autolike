<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/22/2018
 * Time: 6:21 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class Viplikes extends \Moloquent
{
    use HasTimestamps;
    protected $connection = 'mongodb';
    protected $collection = 'viplikes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        '_id', 'username', 'number_success', 'createdAt', 'number_deff', 'status', 'updated_date_time', 'updatedDateTime', 'token', 'created_at', 'updated_at'
    ];
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at'];
    public function getDates()
    {
        return ['created_at', 'updated_at'];
    }
}