<?php

use LasseHaslev\Image\Helpers\ImageFilename;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageFilenameHelperTest extends PHPUnit_Framework_TestCase
{


    protected $imagePath;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        $this->imagePath = __DIR__ . '/../images/kitten1.jpg';
    }

    /** @test */
    public function ads_underscores_if_no_width_or_height_is_set() {
        $path = ImageFilename::filename( $this->imagePath );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-_x_.jpg' );
    }

    /** @test */
    public function add_width_or_height_to_filename() {
        $path = ImageFilename::filename( $this->imagePath, ['width'=>10] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-10x_.jpg' );

        $path = ImageFilename::filename( $this->imagePath, ['height'=>20] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-_x20.jpg' );
    }

    /** @test */
    public function is_setting_resize_flag_when_we_add_resize_boolean() {
        $path = ImageFilename::filename( $this->imagePath, [
            'width'=>10,
            'height'=>20,
            'resize'=>true,
        ] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-10x20-resize.jpg' );
    }

}
