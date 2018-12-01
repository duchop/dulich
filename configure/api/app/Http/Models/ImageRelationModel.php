<?php
/**
 * Created by PhpStorm.
 * User: doduchop
 * Date: 11/30/2018
 * Time: 3:10 PM
 */

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ImageRelationModel extends Model
{

    const CREATED_AT = 'regist_datetime';

    const UPDATED_AT = 'update_datetime';

    protected $table = 'tbl_image_relation';

    protected $primaryKey = 'image_relation_id';

    public function image()
    {
        return $this->belongsTo(ImageModel::class, 'image_id');
    }
}
