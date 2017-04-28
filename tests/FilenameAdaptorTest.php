<?php

use LasseHaslev\Image\Handlers\ImageHandler;
use LasseHaslev\Image\Adaptors\FilenameAdaptor;
use LasseHaslev\Image\Handlers\CropHandler;
use LasseHaslev\Image\Helpers\ImageFilename;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class FilenameAdaptorTest extends PHPUnit_Framework_TestCase
{


    protected $modifier;
    protected $imagePath;

    protected $handler;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        $this->handler = CropHandler::create( __DIR__ . '/../images' );
        $this->handler->setAdaptor( new FilenameAdaptor );
        $this->handler->setCropsFolder(__DIR__ . '/../images/tests/FilenameAdaptor' );
        $this->imageName = 'kitten1.jpg';
        $this->imagePath = __DIR__ . '/../images/' + $this->imageName;
        // $this->modifier = ImageHandler::create( $this->imagePath );
    }

    // Set width
    // Set height
    // Set dynamic width
    // Set dynamic height
    // resize

    /**
     * undocumented function
     *
     * @return void
     */
    protected function getImageName($width = '_', $height = '_')
    {
        $width = 200;
        $height = 200;
        return sprintf( 'kitten1-%sx%s.jpg', $width, $height );
    }


    /** @test */
    public function can_set_width_and_height_in_url() {

        $width = 200;
        $height = 200;
        $filename = ImageFilename::filename( $this->imageName, [
            'width'=>$width,
            'height'=>$height,
        ] );

        $this->handler
            ->handle( $filename )
            ->save( $filename );

        $size = getimagesize( $this->handler->getCropsFolder( $filename ) );
        $this->assertEquals($width, $size[0]);
        $this->assertEquals($height, $size[1]);

    }

    // resize by width
    /** @test */
    public function can_resize_image_by_width() {
        $width = 200;
        $filename = ImageFilename::filename( $this->imageName, [
            'width'=>$width,
        ] );

        $this->handler
            ->handle( $filename )
            ->save( $filename );

        $size = getimagesize( $this->handler->getCropsFolder( $filename ) );
        $this->assertEquals($width, $size[0]);
        $this->assertNotEquals(800, $size[1]);
    }

    /** @test */
    public function can_resize_image_by_height() {
        $height = 200;
        $filename = ImageFilename::filename( $this->imageName, [
            'height'=>$height,
        ] );

        $this->handler
            ->handle( $filename )
            ->save( $filename );

        $size = getimagesize( $this->handler->getCropsFolder( $filename ) );
        $this->assertNotEquals(1280, $size[0]);
        $this->assertEquals($height, $size[1]);
    }
    // resize with flag

    /** @test */
    public function can_resize_image_by_resize_flag_when_width_and_height_is_set() {
        $width = 200;
        $height = 200;
        $filename = ImageFilename::filename( $this->imageName, [
            'width'=>$width,
            'height'=>$height,
            'resize'=>true,
        ] );

        $this->handler
            ->handle( $filename )
            ->save( $filename );

        $size = getimagesize( $this->handler->getCropsFolder( $filename ) );
        $this->assertEquals($width, $size[0]);
        $this->assertEquals(125, $size[1]);
    }

    // Set focus point X
    // Set focus point Y
    // Set focus point X and Y

}
