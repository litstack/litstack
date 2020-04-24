# CRUD Introduction

One of the main functions of Fjord is to make data from the database manageable. For this purpose you can easily create form `fields` for each `column` from the database, each `relation` of a model or other.

## Models

The main component of an admin panel is to manage data. Fjord offers the possibility to make [Laravel Eloquent Models](https://laravel.com/docs/7.x/eloquent) editable and manageable. For a clear administration of models a suitable `index` table and the corresponding `create` and `update` form is needed. Fjord also comes with open source packages to make models `translatable` and `sluggable` and to attach `media`.The following packages are used for this

-   [Astronomic Translatable](https://docs.astrotomic.info/laravel-translatable/)
-   [Spatie Medialibrary](https://docs.spatie.be/laravel-medialibrary/v8/introduction/)
-   [Cviebrock Sluggable](https://github.com/cviebrock/eloquent-sluggable)

### Configuration

The **configuration** for the CRUD Models takes place in the Config folder Crud, each Model gets its own config file named after the model (`{model}Config.php`). The config folder could look like this:

```shell
- Config/
    - Crud/
        - PostConfig.php
        - CommentConfig.php
        ...
```

## Forms

Fjord-Forms provide a convenient way to store, organize and maintain data of many kinds, such as your page-content. You may create as many `Forms` as you like.

`Forms` are divided into Form `collections` to keep the overview. For example, the `Forms` **home** and **faq**, which contain the page-content for the pages **home** and **faq**, can be included in the `collection` **pages**.

### Configuration

In fact, Fjord comes with `Forms` by default: **Pages** and **Settings**. Every page's content is defined by a single config in the `Config/Forms` directory.

```shell
- Config/
    - Forms/
        - Pages/
            - HomeConfig.php
            - FaqConfig.php
            ...
        - Settings/
            - MainConfig.php
            ...
        ...
```
