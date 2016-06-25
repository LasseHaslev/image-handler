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
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
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
        return null;
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
     * Check if we can instantiate the Image modifier
     */
    public function test_image_instantiate()
    {
        $this->assertTrue( true );
    }

    /**
     * Check if we can save image
     */
    public function test_can_save_image()
    {
        $this->assertTrue( true );
    }


    /*
     * Check if we can resize image
     */
    public function test_image_resize()
    {
        // $this->modifier->resize( 100, 100 );
        // return $this->assertTrue( file_exists( $this->imagePath ) );
    }

    /**
     * Resize with one unknown 100x_ or _x100
     */
    public function test_resize_with_one_unknown_size()
    {
        return null;
    }

    /**
     * Crop with focuspoint
     */
    public function test_crop_with_focus_point()
    {
        return null;
    }

    /**
     * Crop image
     */
    public function test_crop_image()
    {
        return null;
    }

    /**
     * Delete crops
     */
    public function test_delete_crops()
    {
        return null;
    }

}
