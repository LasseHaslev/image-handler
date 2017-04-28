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

}
