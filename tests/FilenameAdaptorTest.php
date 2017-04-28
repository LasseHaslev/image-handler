<?php

use LasseHaslev\Image\Handlers\ImageHandler;
use LasseHaslev\Image\Adaptors\FilenameAdaptor;
use LasseHaslev\Image\Handlers\CropHandler;

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
        $this->imagePath = __DIR__ . '/../images/kitten1.jpg';
        // $this->modifier = ImageHandler::create( $this->imagePath );
    }

    // Set width
    // Set height
    // Set dynamic width
    // Set dynamic height

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
        $filename = $this->getImageName( $width, $height );

        $this->handler
            ->handle( $filename )
            ->save( $filename );

        $size = getimagesize( $this->handler->getCropsFolder( $filename ) );

        /**
         * also count it yourself, as I can't reproduce your code
         */
        $this->assertEquals($width, $size[0]);
        $this->assertEquals($height, $size[1]);

    }

}
