# image-handler
PHP image handler gives easy to use function for manipulating images

## Motivation

Resizing and cropping images on web is pain, and i wanted an image-engine that does this for me.

This package and its base concept is greatly inspired by [Croppa](https://github.com/BKWLD/croppa).

## Usage
I use this package mainly in my [Laravel](https://laravel.com/) projects.

Run ```composer require lassehaslev/image``` in your project folder

#### Laravel
If you want to use this package in you laravel project. We automaticly crops and resize the images based on the filename.

Open ```config/app.php``` and add ```LasseHaslev\Image\Providers\LaravelServiceProvider::class``` to ```providers``` array.

## Classes
You can nativly use this package in all php projects.

#### CropHandler
Adds base folder and crops folder and handle image from image path.

If no crops folder is set, we crate crops in same folder as original.
```
$baseFolder = '/image';
$cropsFolder = '/image/crops';
$handler = CropHandler::create( $baseFolder, $cropsFolder );

$this->handler
    ->handle( [
        'name'=>'test-image.jpg',
        'width'=>89,
        'height'=>89,
        'resize'=>true,
    ] )
    ->save( 'test-image-89x89-resize.jpg' );
```

#### Adaptors
You can use adapotors to handle image.
```
class Adaptor implements CropAdaptorInterface
{
    public function transform( $input, $handler = null )
    {
        return [
            'name'=>$input,
            'width'=>300,
            'height'=>200,
            'resize'=>true,
        ];
    }
}
$baseFolder = '/image';
$cropsFolder = '/image/crops';
$handler = CropHandler::create( $baseFolder, $cropsFolder, new Adaptor );

$this->handler
    ->handle( 'originalFilename.jpg' )
    ->save( 'newFilename' );
```
#### ImageModifier
The ```ImageModifier``` is the base image class for manipulating the images. 
```
use LasseHaslev\Image\Modifiers\ImageModifier;
$modifier = ImageModifier::create( { absolute image path } );

// Crop image function
$modifier->crop( $x1, $y1, $x2, $y2 );

// Crop image to width and height based on fucuspoint
$modifier->cropToFit( $width, $height, $focusPointX = 0, $focusPointY = 0 );

// Resize width and height
$modifier->resize( $width, $height );

// Save the new image
$modifier->save( {absolutePath} );

// Example
$modifier->cropToFit( 300, 300 )
    ->save( '/path/to/image.jpg' );
```

#### ImageHandler
The ImageHandler is for handling image and the crops. It extends from ```ImageModifier```.
```
use LasseHaslev\Image\Handlers\ImageHandler;
$modifier = ImageHandler::create( $filepath );

// Remove the crops
$modifier->removeCrops();

// Save
$modifier->save( $pathOrFilename, $isFullPath = false );
```

## Development
I have a problem with my tests. but i dont know why. Sometimes it passes and sometimes it dont.
``` bash
# Install php dependencies
composer install

# Install elixir dependencies
npm install

# run Test driven development through elixir
gulp tdd
```
``

## License
MIT, dawg
