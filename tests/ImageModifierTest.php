<?php

use LasseHaslev\Image\Modifiers\ImageModifier;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageModifierTest extends PHPUnit_Framework_TestCase
{


    protected $modifier;
    protected $imagePath;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        // Throw error if originalFilePath does not exist
        $this->imagePath = __DIR__ . '/../images/kitten1.jpg';
        $this->modifier = ImageModifier::create( $this->imagePath );
    }

    /**
     * Call destroy on the modifier to remove the GD object
     * This prevent error
     */
    public function tearDown()
    {
        $this->modifier->destroy();
    }

    /**
     * Throws error if not an resizeable image
     */
    public function test_if_we_return_error_when_image_is_not_resizeable()
    {
        return null;
    }

    /**
     * Check if we can save image
     */
    public function test_save_image()
    {
        $this->modifier->save( __DIR__ . '/../images/tests/modifier-kitten1.jpg' );
    }

    /**
     * Return width/height
     */
    public function test_return_image_size()
    {
        return null;
    }

    /**
     * Return mime_type
     */
    public function test_return_mime_type()
    {
        return null;
    }

    /*
     * Check if we can resize image
     */
    public function test_image_resize()
    {
        $this->modifier->resize( 100, 100 )
            ->save( __DIR__ . '/../images/tests/modifier-test-image-100x100-resize.jpg' );
    }

    /**
     * Resize with one unknown 100x_ or _x100
     */
    public function test_resize_with_one_unknown_size()
    {
        $this->modifier->resize( null, 100 )
            ->save( __DIR__ . '/../images/tests/modifier-test-image-_x100.jpg' );
        $this->modifier->resize( 100, null )
            ->save( __DIR__ . '/../images/tests/modifier-test-image-100x_.jpg' );
    }

    /**
     * Crop image
     */
    public function test_crop_image()
    {
        $this->modifier->crop( 500,500, 1500,1500 )
            ->save( __DIR__ . '/../images/tests/modifier-test-image-500,1500x500,1500-cropped.jpg' );
    }

    /**
     * Crop to square
     */
    public function test_crop_to_square()
    {
        $this->modifier->cropToFit( 100,100 )
            ->save( __DIR__ . '/../images/tests/modifier-test-image-100x100.jpg' );
    }

    /**
     * Crop with focuspoint
     */
    public function test_crop_with_focus_point()
    {
        $this->modifier->cropToFit( 100,100, 1, 1 )
            ->save( __DIR__ . '/../images/tests/modifier-test-image-100x100-focuspoint.jpg' );
    }

    /**
     * Get width and height
     */
    public function test_get_width_and_height()
    {
        $this->assertInternalType( 'int', $this->modifier->getWidth() );
        $this->assertInternalType( 'int', $this->modifier->getHeight() );
    }

}
