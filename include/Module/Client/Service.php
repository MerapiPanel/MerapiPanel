<?php
namespace MerapiPanel\Module\Client;

use MerapiPanel\Box;
use MerapiPanel\Box\FileLoader;
use MerapiPanel\Box\Module\__Fragment;
use MerapiPanel\Box\Container;
use MerapiPanel\Utility\Http\Request;
use MerapiPanel\Utility\Router;
use MerapiPanel\Views\View;
use Symfony\Component\Filesystem\Path;

class Service extends __Fragment
{

    protected $module;

    public function onCreate(\MerapiPanel\Box\Module\Entity\Module $module)
    {
        error_log("from client: 1");
        $hostname = Request::getInstance()->http("host");

        $container = Box::module();

        if ($hostname && !($hostname == "localhost" || $hostname == "127.0.0.1")) {

            $client_cwd = Path::join($_ENV['__MP_CWD__'], "client", preg_replace("/[^a-z0-9]+/i", "", $hostname));
            if (!file_exists($client_cwd)) {
                return;
            }

            $module->props->cwd = $client_cwd;

            // check path is in access
            preg_match(
                "/^" . preg_replace("/\//", "\\/", (implode("|", array_column($_ENV['__MP_ACCESS__'], "prefix")))) . "/i",
                Request::getInstance()->getPath(),
                $matches
            );

            if (!isset($matches[0]))
            // override box for client
            {

                
               
                /**
                 * @var Container $container
                 * Note - the order must be like below 
                 * 1. Module loader
                 * 2. Reset route
                 * 3. Modify any Module props
                 * 4. Reinitialize box
                 * 5. View loader
                 * By changing the order app won't work as well
                 */
                // 1. Module loader
                $container->setLoader(new ClientModuleLoader(Path::join($client_cwd, "Module")));

                // 2. Reset route
                Router::getInstance()->resetRoute();

                // 3. Modify any Module props
                $container->FileManager->Controller->Guest->register();
                $container->FileManager->Assets->addLoader("buildin", new FileLoader(Path::join($client_cwd, "Assets")));
                $container->FileManager->props->root = Path::join($client_cwd, "content");

                // 4. Reinitialize box
                $container->initialize();


                // 5. View loader
                View::getInstance()->setLoader(new ClientViewLoader($module, [Path::join($_ENV['__MP_CWD__'], "include", "Buildin", "Views")]));
            }
        }
    }
}