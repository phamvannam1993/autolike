<?php
/**
 * Created by PhpStorm.
 * User: ductho1201
 * Date: 12/22/2018
 * Time: 6:21 PM
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;

class ServiceLog extends \Moloquent
{
    use HasTimestamps;
    protected $connection = 'mongodb';
    protected $collection = 'service_logs';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        '_id', 'username', 'password','fullname', 'clone_count', 'nox_clone_count', 'nox_count', 'user_nox', 'vpn_loading', 'name', 'role', 'pass_show', 'email', 'token', 'created_at', 'updated_at'
    ];
    public $timestamps = true;
    protected $dates = ['created_at', 'updated_at'];
    public function getDates()
    {
        return ['created_at', 'updated_at'];
    }
}