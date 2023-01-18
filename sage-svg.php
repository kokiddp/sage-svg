<?php

namespace SageSvg;

use function App\sage;
use BladeUI\Icons\Factory;
use BladeUI\Icons\IconsManifest;
use Illuminate\Filesystem\Filesystem;

function registerBinding()
{
    if (function_exists('\\App\\sage')) {
        sage()->singleton(Factory::class, function () {
            $factory = new Factory(new Filesystem(), new IconsManifest(new Filesystem(), './'));
            $factory->add('default', [
                'path' => get_stylesheet_directory().'/assets/icons/',
                'prefix' => ''
            ]);

            return $factory;
        });
    }
}

function registerBladeTag()
{
    if (function_exists('\\App\\sage')) {
        sage('blade')->compiler()->directive('svg', function ($expression) {
            return "<?php echo e(\\App\\sage(\\BladeUI\\Icons\\Factory::class)->svg($expression)) ?>";
        });
    }
}

if (function_exists('add_action')) {
    add_action('init', __NAMESPACE__.'\registerBinding');
    add_action('init', __NAMESPACE__.'\registerBladeTag');
}
