Twig renderer for the Wireframe output framework
------------------------------------------------

This module is an optional renderer add-on for the Wireframe output framework, adding support for
the Twig templating engine.

## Basic usage

First of all, you need to install both Wireframe and WireframeRenderTwig, and then set up Wireframe
(as instructed at https://wireframe-framework.com/getting-started/). Once that's done, you can open
the bootstrap file (wireframe.php) and instruct Wireframe to use the Twig renderer:

```php
// during Wireframe init (this is the preferred way):
$wireframe->init([
    'renderer' => ['WireframeRendererTwig', [
        'environment' => [
            'autoescape' => 'name', // just an example (this is the default value)
        ],
        'ext' => 'twig', // file extension ('twig' is the default value)
    ]],
]);

// ... or after init (this incurs a slight overhead):
$wireframe->setRenderer('WireframeRendererTwig', [
    // optional settings array
]);
```

## Twig templates

Once you've told Wireframe to use the Twig renderer, by default it will attempt to render all your
views, layouts, and components using Twig. File extension for Twig templates is `.twig`, though you
can override this if you prefer something else (see examples in the "Basic usage" section).

Note that if a Twig file can't be found, Wireframe will automatically fall back to native (`.php`)
file. This is intended to ease migrating from PHP to Twig, and also makes it possible for Twig and
PHP view files to co-exist.

> If you need help with Twig and its syntax, visit https://twig.symfony.com/doc/3.x/.

### Includes (partials)

Twig provides a function for including other templates (`{{ include('some.twig') }}`), and in the
context of Wireframe this translates best to the concept of partials. As such using this function
looks for include files from the Wireframe partials directory:

```
{{ include('header.twig') }}
```

```
.
|-- partials
|   `-- header.twig
```

### Extending Twig

If you want to add functions, filters, globals, etc. to Twig, you can access the Twig Environment
by hooking into `WireframeRendererTwig::initTwig`:

```php
// site/ready.php
$wire->addHookAfter('WireframeRendererTwig::initTwig', function(HookEvent $event) {
    $event->return->addFunction(new \Twig\TwigFunction('hello', function ($value) {
        return "hello " . $value;
    }));
});
```

```
{{ hello('world') }}
```