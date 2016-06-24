<?php

use LasseHaslev\Image\Handlers\ImageCropHandler;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageHandlerTest extends PHPUnit_Framework_TestCase
{

    protected $handler;
    protected $imagePath;

    public function setup()
    {
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
        $this->handler = new ImageCropHandler();
    }


    public function test_image_resize()
    {
        return $this->assertTrue( file_exists( $this->imagePath ) );
    }

}
