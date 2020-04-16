<?php

Route::get("/", [$controller, "show"])->name('edit');
Route::put("/{id}", [$controller, "update"])->name('update');

// Blocks
Route::post("/{id}/blocks/{field_id}", [$controller, "storeBlock"])->name("blocks.store");
Route::put("/{id}/blocks/{field_id}", [$controller, "updateBlock"])->name("blocks.update");
Route::delete("/{id}/blocks/{field_id}", [$controller, "destroyBlock"])->name("blocks.destroy");

// Relations
Route::get("/{id}/relation/{relation}/all", [$controller, "relationIndex"])->name("relation.all");
Route::put("/{id}/relation/{relation}/order", [$controller, "orderRelation"])->name("relation.order");
Route::delete("/{id}/relation/{relation}/{relation_id}",  [$controller, "deleteRelation"])->name("relation.delete");
Route::post("/{id}/relation/{relation}/{relation_id}", [$controller, "createRelation"])->name("relation.store");

// Media
Route::put("/{id}/media/{media_id}", [$controller, 'updateMedia'])->name("media.update");
Route::post("/{id}/media", [$controller, 'storeMedia'])->name("media.store");
Route::delete("{id}/media/{media_id}", [$controller, 'destroyMedia'])->name("media.destroy");
