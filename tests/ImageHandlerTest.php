<?php

use LasseHaslev\Image\Handlers\ImageCropHandler;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageHandlerTest extends PHPUnit_Framework_TestCase
{

    protected $handler;

    public function setup()
    {
        $this->handler = new ImageCropHandler();
    }


    public function test_image_resize()
    {
        return $this->assertTrue( true );
    }

}
