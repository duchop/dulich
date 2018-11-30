<?php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class ApiProductsNgStatusModel extends Model
{

    const CREATED_AT = 'REGIST_DTIME';

    const UPDATED_AT = 'UPDATE_DTIME';

    protected $table = 'API_PRODUCTS_NG_STATUS';

    protected $primaryKey = 'API_PRODUCTS_NG_STATUS_ID';
}
