<?php

use LasseHaslev\Image\Modifiers\ImageModifier;

/**
 * Class DontKnowTest
 * @author Lasse S. Haslev
 */
class ImageModifierSmallImageTest extends PHPUnit_Framework_TestCase
{


    protected $modifier;
    protected $imagePath;

    /*
     * Setup image path and modifier
     */
    public function setup()
    {
        // Throw error if originalFilePath does not exist
        $this->imagePath = __DIR__ . '/../images/kitten-small.jpg';
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

    /** @test */
    public function normalCrop() {
        $this->modifier->cropToFit( 100,200 )
            ->save( __DIR__ . '/../images/tests/kitten-small-100x200.jpg' );
    }

    /** @test */
    public function crop_larger_width() {
        $this->modifier->cropToFit( 300,100 )
            ->save( __DIR__ . '/../images/tests/kitten-small-300x100.jpg' );
    }

    /** @test */
    public function crop_larger_height() {
        $this->modifier->cropToFit( 100,300 )
            ->save( __DIR__ . '/../images/tests/kitten-small-100x300.jpg' );
    }

    /** @test */
    public function crop_small_square() {
        $this->modifier->cropToFit( 100,100 )
            ->save( __DIR__ . '/../images/tests/kitten-small-100x100.jpg' );
    }
    /** @test */
    public function crop_large_square() {
        $this->modifier->cropToFit( 500,500 )
            ->save( __DIR__ . '/../images/tests/kitten-small-500x500.jpg' );
    }

    /** @test */
    public function resize_small() {
        $this->modifier->resize( 100 )
            ->save( __DIR__ . '/../images/tests/kitten-small-100x_.jpg' );
    }

    /** @test */
    public function resize_large() {
        $this->modifier->resize( 500 )
            ->save( __DIR__ . '/../images/tests/kitten-small-500x_.jpg' );
    }

    /** @test */
    public function crop_resize_large() {
        $this->modifier->cropToFit( 500, 1000 )
            ->save( __DIR__ . '/../images/tests/kitten-small-500x1000.jpg' );
    }

}
