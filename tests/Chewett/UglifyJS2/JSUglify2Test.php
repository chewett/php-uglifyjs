<?php

namespace Chewett\UglifyJS2;

class JSUglify2Test extends \PHPUnit_Framework_TestCase
{

    public static $buildDir = __DIR__ . '/../../../build/output/';

    public function setUp() {
        if(!is_dir(self::$buildDir)) {
            mkdir(self::$buildDir);
        }
    }

    public function testCheckVersionWorks() {
        $ug = new JSUglify2();
        $this->assertTrue($ug->checkUglifyJsExists());
    }

    /**
     * @expectedException \Chewett\UglifyJS2\UglifyJs2Exception
     */
    public function testFileNotReadable() {
        $ug = new JSUglify2();
        $ug->uglify(["not_a_file"], "output.js");
    }

    public function testRunningOnJquery() {
        $ug = new JSUglify2();
        $output = $ug->uglify([__DIR__ . '/../../../vendor/components/jquery/jquery.js'], self::$buildDir . 'jquery.min.js');
        $this->assertNotNull($output);
    }

    public function testRunningOnTwitterBootstrap() {
        $ug = new JSUglify2();
        $twitterBootstrapDir = __DIR__ . '/../../../vendor/twbs/bootstrap/js/';
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

    public function testRunningOnJqueryWithMangle() {
        $ug = new JSUglify2();
        $output = $ug->uglify(
            [__DIR__ . '/../../../vendor/components/jquery/jquery.js'],
            self::$buildDir . 'jquery_compressed.min.js',
            ['compress' => '']
        );
        $this->assertNotNull($output);
    }

}
