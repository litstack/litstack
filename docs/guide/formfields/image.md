# Image

A drag and drop image uploader.

Example:

```php
[
    'id' => 'images', // the key for the image-collection you append to your model
    'type' => 'image',
    'translatable' => true,
    'title' => 'Images',
    'hint' => 'Image Collection',
    'width' => 12,
    'image_size' => 4, // cols for the images
    'maxFiles' => 5,
    'crop' => true, // should the image be cropped before upload
    'ratio' => 16/9, // crop ratio
    'square' => true
],
```

| Key          | Required | Description                                                     |
| ------------ | -------- | --------------------------------------------------------------- |
| `id`         | true     | The name of the image collection you defined in your CRUD-Model |
| `type`       | true     | `image`                                                         |
| `title`      | true     | The title for this form field.                                  |
| `hint`       | false    | A short hint that should describe how to use the form field.`   |
| `width`      | false    | Cols of the form field.                                         |
| `image_size` | false    | Cols of one image.                                              |
| `maxFiles`   | false    | Maxmium number of uploadable images.                            |
| `crop`       | false    | Opens a Crop-Tool durnig the upload if set to `true`            |
| `ratio`      | false    | The crop-ratio                                                  |
| `square`     | false    | Display a square thumbnail, recommended for multiple images     |
