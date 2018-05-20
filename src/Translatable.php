<?php
namespace DigitalUnityCa\Translatable;

/**
 * Translatable class
 *
 * Class Translatable
 * @package DigitalUnityCa\Translatable
 */
class Translatable
{
    /** @var string */
    public $separator;

    public function __construct()
    {
        $this->separator = config('backpack.translatable.separator');
    }


    /**
     * Get active languages
     * @param $withAbbr, if is true return [locale_id => abbr] array, if false - only [locale_id]
     * @return bool
     */
    public function isLocalizedInput($inputName): bool
    {
        $pattern = '/^\w{1,}'.$this->separator.'\w{2}$/';
        return preg_match($pattern, $inputName);
    }

    /**
    * Get matches for localized pattern
    * @param string $field
    * @return bool
    */
    public function getLocalePatternMatches($field):array
    {
        $pattern = '/^(\w*)'.$this->separator.'(\w{2})$/';
        preg_match($pattern, $field, $matches);

        return $matches;
    }

    /**
     * Get language id
     * @param $localeAbbr
     * @return int
     */
    public function localeId($localeAbbr = null): int
    {
        $localeAbbr = $localeAbbr??app()->getLocale();

        $language = \Backpack\LangFileManager\app\Models\Language::where('abbr', $localeAbbr)->first();

        if (is_null($language)) {
            throw new LogicException('Locale ['.$localeAbbr.'] doesn\'t exists');
        }

        return $language->id;
    }
}