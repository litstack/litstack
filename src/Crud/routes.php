<?php

if ($type == 'crud') {
    Route::get("/", [$controller, "index"])->name('index');
    Route::post("/index", [$controller, "indexTable"])->name('index.items');
    Route::get("/create", [$controller, "create"])->name('create');
    Route::post("/", [$controller, "store"])->name('store');
    Route::delete("/{id}", [$controller, "destroy"])->name('destroy');
    Route::get("/{id}/edit", [$controller, "edit"])->name('edit');
} else {
    Route::get("/", [$controller, "edit"])->name('edit');
}

Route::put("/{id}", [$controller, "update"])->name('update');

// Media
Route::put("/{id}/media/order", [$controller, 'orderMedia'])->name("media.order");
Route::put("/{id}/media/{media_id}", [$controller, 'updateMedia'])->name("media.update");
Route::post("/{id}/media", [$controller, 'storeMedia'])->name("media.store");
Route::delete("{id}/media/{media_id}", [$controller, 'destroyMedia'])->name("media.destroy");

// Blocks
Route::post("/{id}/blocks/{field_id}", [$controller, "storeBlock"])->name("blocks.store");
Route::put("/{id}/blocks/{field_id}/{block_id}", [$controller, "updateBlock"])->name("blocks.update");
Route::delete("/{id}/blocks/{field_id}/{block_id}", [$controller, "destroyBlock"])->name("blocks.destroy");

// Blocks Media
Route::post("/{id}/blocks/{block_id}/media", [$controller, "storeBlockMedia"])->name("blocks.media.store");
Route::put("/{id}/blocks/{block_id}/media/order", [$controller, 'orderBlockMedia'])->name("blocks.media.order");
Route::put("/{id}/blocks/{block_id}/media/{media_id}", [$controller, "updateBlockMedia"])->name("blocks.media.update");
Route::delete("/{id}/blocks/{block_id}/media/{media_id}", [$controller, "destroyBlockMedia"])->name("blocks.media.destroy");

// Relations
Route::get("/{id}/{relation}/index", [$controller, "relationIndex"])->name("relation.index");
Route::put("/{id}/{relation}/order", [$controller, "orderRelation"])->name("relation.order");
Route::delete("/{id}/{relation}/{relation_id}",  [$controller, "destroyRelation"])->name("relation.delete");
Route::post("/{id}/{relation}/{relation_id}", [$controller, "createRelation"])->name("relation.store");
