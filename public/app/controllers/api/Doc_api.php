<?php 

namespace app\controllers\api;

use app\models\market\ProductCategories_model;
use app\models\Jorge;

class Doc_api
{

    /** */
    public static function tree($store = 904)
    {
        $tree = proxy(
            [ProductCategories_model::class,'tree'],
            [], CACHE_ROOT_TTL,
            PROXY_IGNORE_CACHE
        );
        reply_json([ 'success' => true, 'result' => $tree ]);
        die();
    }


    public static function dbDoc()
    {
        $j = new Jorge();
        reply_json([
            'success' => true,
            'result' => $j->databaseDocumentation()
        ]);
        die();

    }
}