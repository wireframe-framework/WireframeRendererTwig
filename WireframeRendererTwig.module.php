<?php

namespace ProcessWire;

/**
 * Wireframe Renderer Twig
 *
 * @version 0.0.1
 * @author Teppo Koivula <teppo@wireframe-framework.com>
 * @license Mozilla Public License v2.0 https://mozilla.org/MPL/2.0/
 */
class WireframeRendererTwig extends Wire implements Module {

    /**
     * Twig loader
     *
     * @var \Twig\Loader\FilesystemLoader
     */
    protected $loader;

    /**
     * Twig environment
     *
     * @var \Twig\Environment
     */
    protected $twig;

    /**
     * View file extension
     *
     * @var string
     */
    protected $ext = 'twig';

    /**
     * Init method
     *
     * If you want to override any of the parameters passed to Twig Environment, you can do this by
     * providing 'environment' array via the settings parameter:
     *
     * ```
     * $wireframe->setRenderer($modules->get('WireframeRendererTwig')->init([
     *     'environment' => [
     *         'autoescape' => false,
     *         'cache' => '/your/custom/cache/path',
     *     ],
     * ]))
     * ```
     *
     * @param array $settings Additional settings (optional).
     * @return WireframeRendererTwig Self-reference.
     */
    public function ___init(array $settings = []): WireframeRendererTwig {

        // autoload Twig classes
        if (!class_exists('\Twig\Filesystemloader')) {
            require_once(__DIR__ . '/vendor/autoload.php' /*NoCompile*/);
        }

        // init Twig FilesystemLoader and add Wireframe paths
        $this->loader = new \Twig\Loader\FilesystemLoader();
        foreach ($this->wire('modules')->get('Wireframe')->getViewPaths() as $type => $path) {
            $this->loader->addPath($path, $type);
        }

        // init Twig Environment
        $this->twig = new \Twig\Environment($this->loader, array_merge([
            'autoescape' => 'name',
            'auto_reload' => true,
            'cache' => $this->wire('config')->paths->cache . '/WireframeRendererTwig',
            'debug' => $this->wire('config')->debug,
        ], $settings['environment'] ?? []));

        return $this;
    }

    /**
     * Render method
     *
     * @param string $type Type of file to render (view, layout, partial, or component).
     * @param string $view Name of the view file to render.
     * @param array $context Variables used for rendering.
     * @return string Rendered markup.
     * @throws WireException if param $type has an unexpected value.
     */
    public function render(string $type, string $view, array $context = []): string {
        if (!in_array($type, array_keys($this->wire('modules')->get('Wireframe')->getViewPaths()))) {
            throw new WireException(sprintf('Unexpected type (%s).', $type));
        }
        return $this->twig->render('@' . $type . '/' . $view, $context);
    }

    /**
     * Set view file extension
     *
     * @param string $ext View file extension.
     * @return WireframeRendererTwig Self-reference.
     */
    public function setExt(string $ext): WireframeRendererTwig {
        $this->ext = $ext;
        return $this;
    }

    /**
     * Get view file extension
     *
     * @return string View file extension.
     */
    public function getExt(): string {
        return $this->ext;
    }

}
