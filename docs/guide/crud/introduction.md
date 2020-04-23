# CRUD Introduction

One of the main functions of Fjord is to make data from the database manageable. For this purpose you can easily create form `fields` for each `column` from the database, each `relation` of a model or other.

## Models

One of the main components of Fjord is to make models easy to work with. A model is made easily manageable with the `index` table and the `create` and `update` form.

Fjord uses the following packages for CRUD models:

-   [Astronomic Translatable](https://docs.astrotomic.info/laravel-translatable/)
-   [Spatie Medialibrary](https://docs.spatie.be/laravel-medialibrary/v8/introduction/)
-   [Cviebrock Sluggable](https://github.com/cviebrock/eloquent-sluggable)

## Forms

Fjord-Forms provide a convenient way to store, organize and maintain data of many kinds, such as your page-content. You may create as many collections as you like.

In fact, Fjord comes with collection by default: **Pages** and **Settings**. Every page's content is defined by a single config in the `resources/fjord/pages` directory. Think of every page as a single collection.
