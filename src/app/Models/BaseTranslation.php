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
            ->where('locale_id', appLocaleId());
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
        return $query->where('locale_id', appLocaleId());
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
    public function scopeByFieldAndLocaleId($query, $field, $locale)
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
     * @return mixed
     */
    public function scopeByFieldSlugLocaleId($query, $value, $field, $locale=null)
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
     * @return mixed
     */
    public function scopeByValueLike($query, $value)
    {
        return $query->where('value', 'LIKE', '%'.$value.'%');
    }

    /**
     * Get value by value
     * @return mixed
     */
    public function scopeByValue($query, $value)
    {
        return $query->where('value', $value);
    }

}
