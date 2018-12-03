<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class HotelModel extends Model
{

    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_hotels';

    protected $primaryKey = 'hotel_id';

    public function imageRelation()
    {
        return $this->hasMany(ImageRelationModel::class, 'hotel_id');
    }
}
