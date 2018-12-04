<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class TourModel extends Model
{

    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_tours';

    protected $primaryKey = 'tour_id';

    public function imageRelation()
    {
        return $this->hasMany(ImageRelationModel::class, 'tour_id');
    }

    public function getCategoryTour()
    {
        return $this->belongsTo(CategoryTourModel::class, 'category_tour_id');
    }

    public function getItinerary()
    {
        return $this->hasMany(ItineraryModel::class, 'tour_id');
    }

    public function getInclude()
    {
        return $this->hasMany(IncludeModel::class, 'tour_id');
    }
}
