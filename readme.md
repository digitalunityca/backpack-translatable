Create a translation model and a migration for existing Model.
    
To run it, pass the following parameters:

    model: Fully qualified name of the model (ex. 'App\Models\Taster')
    --model-path: Path to the models folder
    --migration-path: Path to the migrations folder
    --migrate: Run the migration at the end (optional).
    --force: Overwrite files (optional).
    
Steps to do:

    1. Add DigitalUnityCa\Translatable\App\Traits\Translatable trait in your model class before use the generator.
    2. Add the relationship in your model. Example for Taster.
    
    use App/Models/TasterTranslation;
    
    ...
    
    /**
     * Taster translations
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations(){
        return $this->hasMany(TasterTranslation::class,'entity_id');
    }
