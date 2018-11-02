<?php

namespace DigitalUnityCa\Translatable\App\Models;

use Backpack\LangFileManager\app\Models\Language;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BaseTranslation extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'locale_id',
        'entity_id',
        'field',
        'slug',
        'value',
    ];


    /**
     * Get value by Field
     * @return mixed
     */
    public function scopeByField($query, $field)
    {
        return $query->where('field', $field)
            ->where('locale_id', localeId());
    }

    /**
     * Get all values by Field
     * @return mixed
     */
    public function scopeByFieldAll($query, $field)
    {
        return $query->where('field', $field);
    }

    /**
     * Get value by SLUG
     * @return mixed
     */
    public function scopeBySlug($query, $slug, $field = 'name')
    {
        return $query->where('slug', $slug)
            ->where('field', $field);
    }

    /**
     * Get value by Active locale
     * @return mixed
     */
    public function scopeByActiveLocale($query)
    {
        return $query->where('locale_id', localeId());
    }

    /**
     * Get value by values arrats
     * @return mixed
     */
    public function scopeByValues($query, string $field, array $values)
    {
        return $query->whereIn('value', $values)
            ->where('field', $field);
    }


    /**
     * Get value by Field and Locale / LocaleId
     * @return mixed
     */
    public function scopeByFieldAndLocaleId(Builder $query, string $field, string $locale):Builder
    {
        if (is_int($locale)) {
            $localeId = $locale;
        } else {
            $language = Language::where('abbr', $locale)->first();
            $localeId = $language->id;
        }

        return $query->where('field', $field)
                     ->where('locale_id', $localeId);
    }

    /**
     * Get value by Field,Value and Locale / LocaleId
     * @return mixed
     */
    public function scopeByFieldValueLocaleId($query, $value, $field, $locale)
    {
        if (is_int($locale)) {
            $localeId = $locale;
        } else {
            $language = Language::where('abbr', $locale)->first();
            $localeId = $language->id;
        }

        return $query->where('field', $field)
            ->where('value', $value)
            ->where('locale_id', $localeId);
    }

    /**
     * Get value by Field,Value and Locale / LocaleId
     * @return Builder
     */
    public function scopeByFieldSlugLocaleId(Builder $query, $value, $field, $locale=null):Builder
    {
        if (is_null($locale)) {
            $localeId = appFrontLocale(true);
        } else {
            if (is_int($locale)) {
                $localeId = $locale;
            } else {
                $language = Language::where('abbr', $locale)->first();
                $localeId = $language->id;
            }
        }

        return $query->where('field', $field)
                     ->where('slug', $value)
                     ->where('locale_id', $localeId);
    }

    /**
     * Get value by value
     * @return Builder
     */
    public function scopeByValueLike(Builder $query, $value):Builder
    {
        return $query->where('value', 'LIKE', '%'.$value.'%');
    }

    /**
     * Get value by value
     * @return Builder
     */
    public function scopeByValue(Builder $query, string $value):Builder
    {
        return $query->where('value', $value);
    }

}
