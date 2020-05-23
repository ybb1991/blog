<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\InternalException;
use App\Models\Storage;
use Auth;

class GoodSku extends Model
{
    use SoftDeletes;

    protected $fillable = ['id','title', 'number', 'picture', 'supplier_id', 'storage_id', 'template_id', 'supp_template_id', 'price', 'trade_price', 'supp_price', 'supp_trade_price', 
    'supp_discount', 'alert_stock', 'supp_shelves', 'supp_sale', 'on_sale', 'supp_category_id', 'supp_brand_id', 'category_id', 'brand_id',
    'settlement_price', 'settlement_discount', 'material_price', 'rough_weight', 'weight', 'discount', 'actual_stock', 'virtual_stock', 'lock_stock', 'stock', 'sold_count', 'supp_sold_count', 'effective_time','limit_sale', 
    'goods_id', 'is_discount', 'set_discount_time', 'record_code', 'hs_code', 'is_new', 'is_hot', 'no_mail', 'supp_no_mail', 'type', 'tag', 'is_change_settle', 'concern'];

    protected $casts = [
        'supp_shelves'      => 'boolean',
        'supp_sale'         => 'boolean',
        'on_sale'           => 'boolean',
        'is_change_settle'  => 'boolean',
        'type'              => 'boolean',
    ];

    protected $dates = ['delete_at'];

    protected static function boot()
    {
        parent::boot();
        // 监听模型创建事件，在写入数据库之前触发
        static::creating(function ($model) {
            // 如果模型的 no 字段为空
            if (!$model->concern) {
                // 调用 findAvailableNo 生成订单流水号
                $model->concern = rand(1, 50);
                // 如果生成失败，则终止创建订单
                if (!$model->concern) {
                    return false;
                }
            }
        });
    }

    public function getPictureAttribute($picture)
    {
        $picture = str_replace(config('url.qiniu_product'), '', $picture);

        return $picture ? config('url.qiniu_product').$picture : '';        
    }

    public function product()
    {
        return $this->belongsTo(Good::class, 'goods_id', 'id')->withTrashed();
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand','brand_id','id')->select(['id', 'name', 'logo', '']);
    }

    public function suppbrand()
    {
        return $this->belongsTo('App\Models\SupplierBrand','supp_brand_id','id')->select(['id', 'name', 'logo', 'origin']);
    }

    public function suppcategory()
    {
        return $this->hasOne('App\Models\SuppCategory', 'id', 'supp_category_id')->select(['id', 'name']);
    }

    public function suppFreeFreightAreas()
    {
        return $this->hasMany(SuppFreeFreight::class, 'sku_id', 'id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'id', 'category_id')->select(['id', 'name']);
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\Supplier','supplier_id', 'id')->select(['id', 'name']);
    }

    public function storage()
    {
        return $this->belongsTo('App\Models\Storage','storage_id', 'id')->select(['id', 'name'])->withDefault(['name'=>'']);
    }

    /**
     * [items description] 废弃
     * @return [type] [description]
     */
    public function items()
    {
        return $this->hasOne(Good::class,'id','goods_id');
    }

    public function orderGood()
    {
        return $this->hasMany(OrderGood::class, 'good_sku_id', 'id');
    }

    public function suppOrderGood()
    {
        return $this->hasMany(SupplierOrGood::class, 'good_sku_id', 'id');
    }


    /*
    *   一对一 关联商品表：good
    *   获取： 商品的总信息
    */
    public function good()
    {
        return $this->belongsTo('App\Models\Good', 'goods_id', 'id')->withTrashed();
    }

    /**
     * 打折商品 good 信息
     */
    public function discountGood()
    {
        return $this->belongsTo('App\Models\Good', 'goods_id', 'id')->where('on_sale', true);
    }

    /*
    *   一对一 关联表：GoodInventory
    *   获取： 商品下面的批次
    */
    public function inventory()
    {
        return $this->hasMany('App\Models\GoodInventory', 'good_sku_id', 'id');
    }

    /**
     * sku 下的所有库存批次
     */
    public function inventories()
    {
        return $this->hasMany(GoodInventory::class, 'good_sku_id', 'id');
    }


    /**
     * 商品下的所有满减满赠相关信息
     */
    public function full()
    {
        return $this->hasManyThrough(
            'App\Models\SuppFullPurchase',
            'App\Models\SuppAdSku',
            'good_sku_id', 
            'id', 
            'id',
            'ad_id'
        ); 
    }

    /**
     * 减库存
     * @param  [type] $amount [description]
     * @return [type]         [description]
     */
    public function decreaseStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('减库存不可小于0');
        }

        return $this->newQuery()->where('id', $this->id)->where('stock', '>=', $amount)->decrement('stock', $amount);
    }

    /**
     * 加虚库存
     * @param  [type] $amount [description]
     * @return [type]         [description]
     */
    public function increaseStock($amount)
    {
        return $this->newQuery()->where('id', $this->id)->where('stock', '>=', $amount)->increment('virtual_stock', $amount);
    }

    /**
     * 恢复库存
     * @param [type] $amount [description]
     */
    public function addStock($amount)
    {
        if ($amount < 0) {
            throw new InternalException('加库存不可小于0');
        }
        $this->increment('stock', $amount);
    }


    /*
    |--------------------------------------------------------------------------
    | 作用域
    |--------------------------------------------------------------------------
    */
    /**
     * 限制查询只包括当前仓库的商品。
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
            return $query->where('supplier_id', $supplierId)->whereIn('storage_id', $storage_ids);
        }
    }

}
