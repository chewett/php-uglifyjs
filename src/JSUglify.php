<?php

namespace Chewett\UglifyJS;

class JSUglify
{
    /** @var string Path to the uglifyjs script */
    private static $location = 'uglifyjs';
    /** @var array List of options allowable to be used for the program */
    private static $options = [
        'source-map' => 'string',
        'source-map-root' => 'string',
        'source-map-url' => 'string',
        'source-map-include-sources' => 'bool',
        'in-source-map' => 'string',
        'screw-ie8' => 'bool',
        'expr' => 'string',
        'prefix' => 'string',
        'beautify' => 'string',
        'mangle' => 'string',
        'reserved' => 'string',
        'compress' => 'string',
        'define' => 'string',
        'encode' => 'string',
        'comments' => 'string',
        'preamble' => 'string',
        'stats' => 'bool',
        'acorn' => 'bool',
        'spidermonkey' => 'bool',
        'self' => 'bool',
        'wrap' => 'string',
        'export-all' => 'bool',
        'lint' => 'bool',
        'verbose' => 'bool',
        'version' => 'bool',
        'noerr' => 'bool',
        'bare-returns' => 'bool',
        'keep-fnames' => 'bool',
        'reserved-file' => 'array',
        'reserve-domprops' => 'bool',
        'mangle-props' => 'bool',
        'mangle-regex' => 'bool',
        'name-cache' => 'string',
        'pure-funcs' => 'array',
        'dump-spidermonkey-ast' => 'bool',
        'quotes' => 'string'
    ];

    /**
     * @param $location
     * @throws UglifyJSException
     */
    public function setLocation($location) {
        if(file_exists($location)) {
            self::$location = $location;
        }else{
            throw new UglifyJSException("Cannot find executable in given location");
        }
    }

    /**
     * @return string Location of the uglifyjs script
     */
    public function getLocation() {
        return self::$location;
    }

    /**
     * Internal function used to validate that the uglifyJS script exists and works (to some degree)
     * @return bool
     */
    public function checkUglifyJsExists() {
        $command = self::$location . " -V";
        exec($command, $outputText, $returnCode);
        if($returnCode == 0) {
            return true;
        }else{
            return false;
        }
    }

    /**
     * Calls the uglifyjs script and minifies the Javascript
     * @param array $files List of filenames to minimise
     * @param string $outputFilename Filename to output the javascript to
     * @param array $options Options to pass to the script
     * @return string Full output of the executable
     * @throws UglifyJSException Thrown when something goes wrong with running the script
     */
    public function uglify(array $files, $outputFilename, array $options = []) {
        foreach($files as $filename) {
            if(!is_readable($filename)) {
                throw new UglifyJSException("Filename " . $filename . " is not readable");
            }
        }
        $safeOutputFilename = escapeshellarg($outputFilename);
        $optionsString = $this->validateOptions($options);
        $fileNames = implode(' ', array_map('escapeshellarg', $files));

        $commandString = self::$location . " {$fileNames} --output {$safeOutputFilename} {$optionsString}";

        exec($commandString, $output, $returnCode);
        if($returnCode !== 0) {
            throw new UglifyJSException("Failed to run uglifyjs, something went wrong... command: " . $commandString);
        }
        return $output;
    }

    /**
     * Internal functions to validate and produce the options string
     * @param array $options Array of options to pass to the script
     * @return string Full options string to be used for the script
     * @throws UglifyJSException Thrown when something is passed in as an option that isnt valid
     */
    private function validateOptions($options) {
        $optionsString = '';
        foreach($options as $option => $value) {
            if(!array_key_exists($option, self::$options)) {
                throw new UglifyJSException('Option not supported');
            }

            $optionType = self::$options[$option];
            if($optionType === 'bool') {
                $optionValue = ($value ? 'true' : 'false');
                $optionsString .= "--{$option}={$optionValue} ";
            }elseif($optionType === 'string') {
                if($value === '') {
                    $optionsString .= "--{$option} ";
                }else{
                    $optionValue = escapeshellarg($value);
                    $optionsString .= "--{$option}={$optionValue} ";
                }

            }elseif($optionType === 'array') {
                throw new UglifyJSException('Array type not supported yet');
            }else{
                throw new UglifyJSException('Option type ' . $option . ' not supported');
            }
        }
        return $optionsString;
    }

}