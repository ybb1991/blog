<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Storage;
use Auth;
// use function GuzzleHttp\json_decode;

class Good extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name','supplier_id','storage_id','category_id', 'supp_brand_id', 'supp_category_id', 'concern','title','main_img','picture', 
        'introduce','brand_id','cat','tag_id','property_ids','in_price','low_price','height_price', 'low_trade_price', 
        'height_trade_price', 'on_sale', 'sold_count', 'supp_sold_count', 'total_stock','content','saymore', 'is_new', 'is_hot', 
        'set_category', 'set_brand', 'sale_time', 'is_change_sale'
    ];

    protected $casts = [
        'picture'           => 'json',
        'on_sale'           => 'boolean', // on_sale 是一个布尔类型的字段
        'set_category'      => 'boolean',
        'set_brand'         => 'boolean',
        'is_change_sale'    => 'boolean',
    ];

    protected $appends = ['picture_url'];

    protected $dates = ['delete_at'];

    public function getMainImgAttribute($main_img)
    {
        return config('url.qiniu_product').$main_img;
    }

    public function getOnSaleAttribute($sale)
    {
        $data = ['下架', '上架'];
        return $data[$sale];
    }

    // app 人气值
    public function getConcernAttribute($concern)
    {
        if($concern < 300){
            $re = ($concern + 8)*3;
        }else if($concern >= 300 && $concern < 500){
            $re = ($concern + 8)*2;
        }else{
            $re = $concern;
        }
        return $re;
    }

    public function getPictureUrlAttribute($picture)
    {
        $data = [];
        if($this->picture){
            foreach ($this->picture as $key => $value) {
                $data[] = config('url.qiniu_product') . $value;
            }
        }
        return $data;
        
    }

    // public function getPictureAttribute($picture)
    // {
    //     foreach ($picture as $key => $value) {
    //         $data[] = config('url.qiniu_product') . $value;
    //     }
    //     return $data;
    // }
    // 与商品SKU关联(二版使用)
    public function productSku()
    {
        return $this->hasMany('App\Models\GoodSku', 'goods_id', 'id');
    }

    // 与商品SKU关联(原来，废弃)
    public function skus()
    {
        return $this->hasMany('App\Models\GoodSku', 'goods_id', 'id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    // public function brand()
    // {
    //     return $this->belongsTo('App\Models\Brand','brand_id','id');
    // }

    public function suppbrand()
    {
        return $this->belongsTo('App\Models\SupplierBrand','supp_brand_id','id');
    }

    /*
    *   关联商品的分类 ： Category
    */
    // public function category()
    // {
    //     return $this->hasOne('App\Models\Category', 'id', 'category_id')->with('category','categoryPrev');
    // }

    // public function onlyCategory()
    // {
    //     return $this->belongsTo('App\Models\Category', 'category_id', 'id');
    // }

    public function suppcategory()
    {
        return $this->belongsTo('App\Models\SuppCategory', 'supp_category_id', 'id')->select(['id', 'name', 'p_id']);
    }

    // public function tag()
    // {
    //     return $this->belongsTo('App\Models\Tag','tag_id','id');
    // }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier','supplier_id', 'id')->select(['id', 'name']);
    }

    public function storage()
    {
        return $this->belongsTo('App\Models\Storage','storage_id', 'id')->withDefault(['name'=>''])->select(['id', 'name', 'province']);
    }

    /*
    |--------------------------------------------------------------------------
    | 作用域
    |--------------------------------------------------------------------------
    */

    /**
     * 限制查询只包括当前仓库的物流。
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmin($query)
    {
        $storage_ids = Auth::user()->storage_ids;
        $isAdmin = Auth::user()->is_admin;
        $supplierId = Auth::user()->supplier_id;

        if($isAdmin!=1){
            return $query->whereIn('storage_id', $storage_ids)->where('supplier_id', $supplierId);
        }else{
            $storage_ids = Storage::where('supplier_id', $supplierId)->where('is_used', 1)->get(['id']);
            return $query->with('storage')->where('supplier_id', $supplierId)->whereIn('storage_id', $storage_ids);
        }
    }

    /**
    *
    *   查询某个供货商下的 合作了的 品牌 的商品
    **/
    public function scopeSuppCooper($query)
    {
        return $query->join('supp_brands',function($join){
                        $join->on('supp_brands.supplier_id','=','goods.supplier_id')
                              ->on('supp_brands.brand_id','=','goods.brand_id')
                              ->where('supp_brands.status',1);
                    })->select('goods.*');
    }

}
