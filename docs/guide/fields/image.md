# Image

A drag and drop image uploader using Spatie's [medialibary](https://docs.spatie.be/laravel-medialibrary/v7/introduction/).

## Example

```php
$form->image('images') // images is the corresponding media collection.
    ->translatable()
    ->title('Images')
    ->hint('Image Collection.')
    ->maxFiles(5)
    ->crop(true) // Should the image be cropped before upload.
    ->ratio(16/9) // Crop ratio.
```

For the case that the first image from the list should be used as a preview image, you can use `firstBig` to display the first image bigger to show that the first image has a bigger meaning.

```php
$form->image('images') // images is the corresponding media collection.
    ->title('Images')
    ->firstBig()
    ->hint('The first image is the preview image.')
```

### Preparing the Model

Add the image attribute to your model:

```php
public function getImagesAttribute()
{
    return $this->getMedia('images');
}
```

## Methods

| Method         | Description                                                   |
| -------------- | ------------------------------------------------------------- |
| `title`        | The title for this form field.                                |
| `translatable` | Should the field be translatable.                             |
| `hint`         | A short hint that should describe how to use the form field.` |
| `cols`         | Cols of the form field.                                       |
| `maxFiles`     | Maxmium number of uploadable images.                          |
| `crop`         | Opens a Crop-Tool before the upload if set to `true`          |
| `ratio`        | The crop-ratio.                                               |
| `firstBig`     | Display's the first image bigger.                             |
