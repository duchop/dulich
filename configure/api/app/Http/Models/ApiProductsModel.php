<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProductsModel extends Model
{

    const CREATED_AT = 'REGIST_DTIME';

    const UPDATED_AT = 'UPDATE_DTIME';

    protected $table = 'API_PRODUCTS';

    protected $primaryKey = 'API_PRODUCTS_ID';
}
