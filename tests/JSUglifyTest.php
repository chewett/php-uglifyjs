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

    /**
     * Get the build directory ready to dump out data to it
     */
    public function setUp() {
        if(!is_dir(self::$buildDir)) {
            mkdir(self::$buildDir);
        }
    }

    /**
     * Simple provider to make tests run with a couple different options when testing php-uglifyjs
     * @return array Provider details
     */
    public function optionsHeaderfileProvider() {
        $headerfilePath = __DIR__ . "/../build/headerfile.js";

        return [
            'No options no header files' => [],
            'No options, header file' => [$headerfilePath],
            'Compress option, no header file' => [null, ['compress' => '']],
            'Compress option, header file' => [$headerfilePath, ['compress' => '']]
        ];
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
     * @dataProvider optionsHeaderfileProvider
     */
    public function testRunningOnJquery($headerfile=null, $options=[]) {
        $outputFilename = self::$buildDir . 'jquery.min.js';
        $ug = new JSUglify();
        $output = $ug->uglify([__DIR__ . '/../vendor/components/jquery/jquery.js'], $outputFilename, $options, $headerfile);
        $this->assertNotNull($output);

        $this->assertFileExists($outputFilename);
    }

    /**
     * Tests to see if running uglify on an empty file works (Expected to work as normal)
     * @dataProvider optionsHeaderfileProvider
     */
    public function testRunningOnEmptyFile($headerfile=null, $options=[]) {
        $outputFilename = self::$buildDir . 'emptyFile.js';
        $ug = new JSUglify();
        $output = $ug->uglify([__DIR__ . '/../build/emptyFile.js'], $outputFilename, $options, $headerfile);
        $this->assertNotNull($output);

        $this->assertFileExists($outputFilename);
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
     * @dataProvider optionsHeaderfileProvider
     */
    public function testRunningOnTwitterBootstrap($headerfile=null, $options=[]) {
        $outputFilename = self::$buildDir . 'bootstrap.min.js';

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
        ], $outputFilename, $options, $headerfile);
        $this->assertNotNull($output);

        $this->assertFileExists($outputFilename);
    }

}
