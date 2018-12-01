<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 12/1/2018
 * Time: 11:17 PM
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class HotelCategoryModel extends Model
{

    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_hotel_category';

    protected $primaryKey = 'hotel_category_id';
}