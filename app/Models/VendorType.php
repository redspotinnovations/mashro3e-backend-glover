<?php

namespace App\Models;

class VendorType extends BaseModel
{


    protected $fillable = ['name', 'description', 'slug', 'is_active', 'color'];
    protected $appends = ['formatted_date', 'logo', 'website_header', 'has_banners'];
    protected $casts = [
        'id' => 'int',
        'is_active' => 'int',
    ];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->useFallbackUrl('' . url('') . '/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
        $this
            ->addMediaCollection('feature_image')
            ->useFallbackUrl('' . url('') . '/images/default.png')
            ->useFallbackPath(public_path('/images/default.png'));
    }


    public function getLogoAttribute()
    {
        return $this->getFirstMediaUrl('logo');
    }

    public function getWebsiteHeaderAttribute()
    {
        return $this->getFirstMediaUrl('website_header');
    }


    public function getIsParcelAttribute()
    {
        return $this->slug == "parcel";
    }

    public function getIsServiceAttribute()
    {
        return $this->slug == "service";
    }

    public function getHasBannersAttribute()
    {
        $count = Banner::whereHas('vendor', function ($q) {
            return $q->whereVendorTypeId($this->id);
        })->count();
        return ($count != null && $count > 0) ? 1 : 0;
    }

    public function scopeAssignable($query)
    {
        return $query->where('slug', '!=', "taxi");
    }

    public function scopeSales($query)
    {
        return $query->whereNotIn('slug', ["taxi", "booking", "service", "parcel"]);
    }
}
