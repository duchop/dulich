<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TransportationModel extends Model
{

    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_transportation';

    protected $primaryKey = 'transportation_id';

    public function imageRelation()
    {
        return $this->hasMany(ImageRelationModel::class, 'transportation_id');
    }

    public function getTravelTime()
    {
        return $this->hasMany(TravelTimeModel::class, 'transportation_id');
    }

    public function getInclude()
    {
        return $this->hasMany(IncludeModel::class, 'transportation_id');
    }
}
