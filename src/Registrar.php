<?php

namespace Tomodomo\WpAssetRegistrar;

use function Stringy\create as s;

class Registrar
{
    /**
     * The arguments to use
     *
     * @var array
     */
    private $args = [];

    /**
     * The registered scripts
     *
     * @var array
     */
    private $scripts = [];

    /**
     * The registered styles
     *
     * @var array
     */
    private $styles = [];

    /**
     * Instantiate the library
     *
     * @param array $args
     *
     * @return Registrar
     */
    public function __construct($args = [])
    {
        $defaults = [
            'basePath' => trailingslashit(dirname(ABSPATH)),
            'urlPath'  => '/',
        ];

        $this->args = wp_parse_args($args, $defaults);

        return $this;
    }

    /**
     * Register a script
     *
     * @param string $name
     * @param string $path
     * @param array $args
     *
     * @return bool
     */
    function addScript(string $name, string $path, array $args = [])
    {
        if (($this->scripts[$name] ?? false) !== false) {
            throw new \Exception('A script with this slug has already been registered.');
        }

        $defaults = [
            'dependencies' => [],
            'footer'       => true,
            'version'      => null,
        ];

        // Handle cases where a full URL is passed in
        if (s($path)->startsWith('http')) {
            $defaults['url'] = $path;
        } else {
            $defaults['version'] = filemtime($this->args['basePath'] . $path);
            $defaults['url']     = $this->args['urlPath'] . $path;
        }

        $args = wp_parse_args($args, $defaults);

        // Add to the global store
        $this->scripts[] = $name;

        return wp_register_script(
            $name,
            $args['url'],
            $args['dependencies'],
            $args['version'],
            $args['footer']
        );
    }

    /**
     * Register a stylesheet
     *
     * @param string $name
     * @param string $path
     * @param array $args
     *
     * @return bool
     */
    function addStyle(string $name, string $path, array $args = [])
    {
        if (($this->styles[$name] ?? false) !== false) {
            throw new \Exception('A style with this slug has already been registered.');
        }

        $defaults = [
            'dependencies' => [],
            'media'        => 'all',
            'version'      => null,
        ];

        // Handle cases where a full URL is passed in
        if (s($path)->startsWith('http')) {
            $defaults['url'] = $path;
        } else {
            $defaults['version'] = filemtime($this->args['basePath'] . $path);
            $defaults['url']     = $this->args['urlPath'] . $path;
        }

        $args = wp_parse_args($args, $defaults);

        // Add to the global store
        $this->styles[] = $name;

        return wp_register_style(
            $name,
            $args['url'],
            $args['dependencies'],
            $args['version'],
            $args['media']
        );
    }

    /**
     * Enqueue scripts, optionally filtered to a selection of scripts
     *
     * @param array $filter
     *
     * @return void
     */
    public function enqueueScripts($filter = [])
    {
        foreach ($this->getScripts() as $script) {
            // If $filter is not empty, but the script is not in the
            // $filter, skip to the next script
            if (!empty($filter) && !in_array($script, $filter)) {
                continue;
            }

            wp_enqueue_script($script);
        }

        return;
    }

    /**
     * Enqueue scripts, optionally filtered to a selection of scripts
     *
     * @param array $filter
     *
     * @return void
     */
    public function enqueueStyles($filter = [])
    {
        foreach ($this->getStyles() as $style) {
            // If $filter is not empty, but the style is not in the
            // $filter, skip to the next script
            if (!empty($filter) && !in_array($style, $filter)) {
                continue;
            }

            wp_enqueue_style($style);
        }

        return;
    }

    /**
     * Get the names of the registered scripts
     *
     * @return array
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * Get the names of the registered styles
     *
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }
}
