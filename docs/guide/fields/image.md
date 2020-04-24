# Image

A drag and drop image uploader using Spatie's [medialibary](https://docs.spatie.be/laravel-medialibrary/v7/introduction/).

## Example

```php
$form->image('images') // images is the corresponding media collection.
    ->translatable()
    ->title('Images')
    ->placeholder('Title')
    ->hint('Image Collection.')
    ->maxFiles(5)
    ->crop(true) // Should the image be cropped before upload.
    ->ratio(16/9) // Crop ratio.
    ->square();
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
| `square`       | Display a square thumbnail, recommended for multiple images   |
