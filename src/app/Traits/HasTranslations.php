<?php
namespace DigitalUnityCa\Translatable\App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Translatable;

trait HasTranslations
{

    /**
     * Return translatable fields
     * @return mixed
     */
    public function getTranslatableFields()
    {
        return $this->translatable;
    }

    /**
     * Override __get() to get the translations
     *
     * @param string $property
     * @return mixed
     */
    public function __get($key)
    {

        $translatable = $this->getTranslatableFields();

        if (Translatable::isLocalizedInput($key) && isset($translatable)){
            $matches  = Translatable::getLocalePatternMatches($key);
            $field = $matches[1];

            if (in_array($field, $translatable)) {

                if (!$this->translations){
                    $this->load('translations');
                }

                $translations = $this->translations;

                $translation = $translations
                    ->where('field',$field)
                    ->where('entity_id', $this->id)
                    ->where('locale_id', Translatable::localeId($matches[2]))
                    ->first();

                return $translation->value??'';
            }
        }

        return parent::__get($key);
    }

    /**
     * Get slug
     *
     * @param string $property
     * @return mixed
     */
    public function __slug($key='name', $locale = null)
    {
        if (in_array($key, $this->getTranslatableFields())) {

            if (!$this->translations){
                $this->load('translations');
            }

            $translations = $this->translations;

            // get the translation
            $translation = $translations
                ->where('field',$key)
                ->where('entity_id', $this->id)
                ->where('locale_id', Translatable::localeId($locale))
                ->first();

            return $translation->slug??'';
        }

        return '';
    }

    /**
     * Save translations for model
     * @return void
     */
    public function saveTranslations()
    {
        $data = request()->all();

        if ($this->getTranslatableFields()){

            foreach ($data as $field=>$value) {
                if (Translatable::isLocalizedInput($field) && $value) {
                    $matches  = Translatable::getLocalePatternMatches($field);
                    $localeId = Translatable::localeId($matches['2']);

                    if (in_array($matches[1],$this->getTranslatableFields())){

                        $this->translations()->firstOrCreate([
                            'entity_id' =>  $this->id,
                            'field'     =>  $matches[1],
                            'locale_id' =>  $localeId
                        ])->update([
                            'value' =>  $value,
                            'slug'  =>  str_slug($value)
                        ]);

                    }
                }
            }

        }

        return $this;

    }

}
