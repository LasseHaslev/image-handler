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

    /** @test */
    public function is_adding_focus_point_format_if_one_of_focus_point_options_are_set() {
        // Focus X
        $path = ImageFilename::filename( $this->imagePath, [
            'width'=>10,
            'height'=>20,
            'focusX'=>-0.55678,
        ] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-10x20-[-0.55678x0].jpg' );

        // Focus Y
        $path = ImageFilename::filename( $this->imagePath, [
            'width'=>10,
            'height'=>20,
            'focusY'=>-0.55678,
        ] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-10x20-[0x-0.55678].jpg' );
    }

    /** @test */
    public function is_adding_focus_point_format_if_both_focus_point_options_is_set() {
        $path = ImageFilename::filename( $this->imagePath, [
            'width'=>10,
            'height'=>20,
            'focusX'=>-0.55678,
            'focusY'=>0.12345,
        ] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-10x20-[-0.55678x0.12345].jpg' );
    }

    /** @test */
    public function does_not_show_focus_point_if_resize_is_set_to_true() {
        $path = ImageFilename::filename( $this->imagePath, [
            'width'=>10,
            'height'=>20,
            'focusX'=>-0.55678,
            'focusY'=>0.12345,
            'resize'=>true,
        ] );
        $this->assertEquals( $path, __DIR__ . '/../images/kitten1-10x20-resize.jpg' );
    }

}
