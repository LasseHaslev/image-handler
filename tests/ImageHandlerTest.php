<?php

use LasseHaslev\Image\Handlers\ImageHandler;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageHandlerTest extends PHPUnit_Framework_TestCase
{


    protected $modifier;
    protected $imagePath;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        $this->imagePath = __DIR__ . '/../images/test-image.jpg';
        $this->modifier = ImageHandler::create( $this->imagePath );
    }

    /**
     * Call destroy on the modifier to remove the GD object
     * This prevent error
     */
    public function tearDown()
    {
        $this->modifier->destroy();
    }

    /*
     * Can save images in same folder
     */
    public function test_save_images_in_same_folder()
    {
    }

    /*
     * Can save images in different folder
     */
    public function test_save_images_in_different_folder()
    {
    }

    /*
     * Can remove crops from same folder
     */
    public function test_remove_crops_in_same_folder()
    {
    }

    /*
     * Can remove crops from different folder
     */
    public function test_remove_crops_in_different_folder()
    {
    }

}
