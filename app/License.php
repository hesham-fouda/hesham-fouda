<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property Collection options
 */
class License extends Model
{
    use SoftDeletes;

    /**
     * License constructor.
     *
     * @param  array  $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        if(is_null($this->getAttribute('options')))
            $this->setAttribute('options', collect([
                'runTime' => false,
                'executions' => false,
                'lockTo' => false,
                'expiredDate' => false,
                'maxDays' => false,
                'assemblyName' => false,
                'assemblyToken' => false,
                'assemblyMinVersion' => false,
                'assemblyMaxVersion' => false
            ]));
        if(is_null($this->getAttribute('optionsData')))
            $this->setAttribute('optionsData', collect([
                'runTime' => null,
                'executions' => null,
                'lockTo' => null,
                'expiredDate' => null,
                'maxDays' => null,
                'assemblyName' => null,
                'assemblyToken' => null,
                'assemblyMinVersion' => null,
                'assemblyMaxVersion' => null
            ]));
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'uid', 'appName', 'serial', 'deactivateCode', 'generatedDate', 'maxDays',
        'options', 'optionsData', 'userData', 'supportId', 'features', 'client_id', 'order_id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'generatedDate',
        'created_at',
        'updated_at',
        'deleted_at',
        'deactivate_at',
        'deactivate_verified'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'collection',
        'optionsData' => 'collection',
        'userData' => 'collection',
        'features' => 'collection'
    ];

    /**
     * Get all of the owning models.
     */
    public function Client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get all of the owning models.
     *  @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get all of the owning models.
     */
    public function getApplicationattribute()
    {
        return $this->Package->Product;
    }

    /**
     * Get all of the owning models.
     */
    public function getPackageattribute()
    {
        return $this->Order->Package;
    }
}
