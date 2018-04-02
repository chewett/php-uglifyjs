<?php

namespace Chewett\UglifyJS\Test;

use Chewett\UglifyJS\JSUglify;
use Chewett\UglifyJS\UglifyJSException;

/**
 * Class JSUglifyTest
 * @package Chewett\UglifyJS\Test
 * @author Christopher Hewett <chewett@hotmail.co.uk>
 */
class JSUglifyTest extends \PHPUnit_Framework_TestCase
{

    public static $buildDir = __DIR__ . '/../build/output/';

    public function setUp() {
        if(!is_dir(self::$buildDir)) {
            mkdir(self::$buildDir);
        }
    }

    /**
     * Very basic check to see if the jsuglify version flag works
     */
    public function testCheckVersionWorks() {
        $ug = new JSUglify();
        $this->assertTrue($ug->checkUglifyJsExists(), "Test to run jsuglify failed, is it installed?");
    }


    /**
     * Tests to see if it fails with the expected exception when ran on a non file
     * @expectedException \Chewett\UglifyJs\UglifyJSException
     */
    public function testFileNotReadable() {
        $ug = new JSUglify();
        $ug->uglify(["not_a_file"], "output.js");
    }

    /**
     * Tests to see if running the file on a single file works
     */
    public function testRunningOnJquery() {
        $ug = new JSUglify();
        $output = $ug->uglify([__DIR__ . '/../vendor/components/jquery/jquery.js'], self::$buildDir . 'jquery.min.js');
        $this->assertNotNull($output);
    }

    /**
     * This test purposely sets a bad executable so that when we try and check to see if it exists it fails to run
     */
    public function testUglifyJsFailsWhenMissingExe() {
        $ug = new JSUglify();
        $ug->setUglifyBinaryPath("not_uglifyjs");
        $this->assertFalse($ug->checkUglifyJsExists());
    }

    /**
     * Test to make sure that an exception is thrown when uglify is ran when the exe is missing
     * @expectedException \Chewett\UglifyJs\UglifyJSException
     */
    public function testRunningUglifyJsWhenMissingExe() {
        $ug = new JSUglify();
        $ug->setUglifyBinaryPath("not_uglifyjs");
        $ug->uglify([__DIR__ . '/../vendor/components/jquery/jquery.js'], self::$buildDir . 'jquery.min.js');
    }

    /**
     * Tests to see if minifying multiple files throws an error
     */
    public function testRunningOnTwitterBootstrap() {
        $ug = new JSUglify();
        $twitterBootstrapDir = __DIR__ . '/../vendor/twbs/bootstrap/js/';
        $output = $ug->uglify([
            $twitterBootstrapDir . "affix.js",
            $twitterBootstrapDir . "alert.js",
            $twitterBootstrapDir . "button.js",
            $twitterBootstrapDir . "collapse.js",
            $twitterBootstrapDir . "dropdown.js",
            $twitterBootstrapDir . "modal.js",
            $twitterBootstrapDir . "popover.js",
            $twitterBootstrapDir . "scrollspy.js",
            $twitterBootstrapDir . "tab.js",
            $twitterBootstrapDir . "tooltip.js",
            $twitterBootstrapDir . "transition.js"
        ], self::$buildDir . 'bootstrap.min.js');
        $this->assertNotNull($output);
    }

    /**
     * Tests to see if mangling jquery will not throw an error
     */
    public function testRunningOnJqueryWithMangle() {
        $ug = new JSUglify();
        $output = $ug->uglify(
            [__DIR__ . '/../vendor/components/jquery/jquery.js'],
            self::$buildDir . 'jquery_compressed.min.js',
            ['compress' => '']
        );
        $this->assertNotNull($output);
    }

}
