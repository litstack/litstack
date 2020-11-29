<?php

namespace Tests\Crud\Fixtures;

use Ignite\Crud\Models\LitFormModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DummyLitFormModel extends LitFormModel
{
    public $table = 'dummy_lit_form_model';
    public $timestamps = false;
    protected $translationModel = DummyLitFormModelTranslations::class;
    protected $translationForeignKey = 'lit_form_id';
    public $fillable = [
        'form_type', 'value', 'config_type',
    ];
    protected $with = [
        'translations', 'media',
    ];
    protected $casts = [
        'value' => 'json',
    ];

    public static function schemaUp()
    {
        Schema::create('dummy_lit_form_model', function (Blueprint $table) {
            $table->id();
            $table->string('config_type')->nullable();
            $table->string('form_type')->nullable();
            $table->text('value')->nullable();
        });
        Schema::create('dummy_lit_form_model_translations', function (Blueprint $table) {
            $table->translates('lit_forms', 'lit_form_id');
            $table->text('value')->nullable();
        });
    }

    public static function schemaDown()
    {
        Schema::dropIfExists('dummy_lit_form_model');
        Schema::dropIfExists('dummy_lit_form_model_translations');
    }
}
