<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/22/2018
 * Time: 11:29 AM
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class TravelTimeModel extends Model
{
    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_travel_time';

    protected $primaryKey = 'travel_time_id';
}