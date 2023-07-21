<?php 

namespace app\controllers\api;

use app\core\ProxyTrait;
use app\core\Render;

use app\models\market\ProductCategories_model;
use app\models\Jorge;

class Doc_api
{
    use ProxyTrait;

    /** */
    public static function tree($store = 904)
    {
        $tree = self::proxy(
            [ProductCategories_model::class,'tree'],
            [], CACHE_ROOT_TTL,
            PROXY_IGNORE_CACHE
        );
        Render::json([ 'success' => true, 'result' => $tree ]);
        die();
    }


    public static function dbDoc()
    {
        $j = new Jorge();
        Render::json([
            'success' => true,
            'result' => $j->databaseDocumentation()
        ]);
        die();

    }
}