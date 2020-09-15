<?php

namespace Ignite\Support\Macros;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;

class BlueprintTranslates
{
    /**
     * Register the macro.
     *
     * @return void
     */
    public function register()
    {
        Blueprint::macro('translates', function ($table, $relationColumn = null) {
            if (is_null($relationColumn)) {
                $relationColumn = Str::singular($table).'_id';
            }

            $this->bigIncrements('id');
            $this->unsignedBigInteger($relationColumn)->unsigned()->index();
            $this->string('locale')->index();
            $this->unique([$relationColumn, 'locale']);
            $this->foreign($relationColumn)->references('id')->on($table)->onDelete('cascade');
        });
    }
}
