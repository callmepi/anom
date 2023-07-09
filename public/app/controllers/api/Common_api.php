<?php 

namespace app\controllers\api;

use \Registry;
use app\models\market\Product_model;
use app\models\market\ProductCategories_model;

/** Common_api
 * ---
 * Controller for handling common API calls
 * about main tables of the project;
 * 
 * includes aglo for passing special filters and sorting
 * (check parse_Where_OrderBy() method)
 */
class Common_api
{

    /** APITables
     * Method returns allowed tables to accept API calls
     * The return is an array of [label => data-source] pairs
     * where 'label' is a friendly name of the datasource
     * (and 'datasource' the actual table/data-source)
     */
    public static function APITables()
    {
        /** allowed tables for api call
         * @return: (array) an of api-call arrays
         * each api-call array has the folloing form:
         * [ label => [
         *     'table' => (string) actual table in database,
         *     (optional) 'filter' => (array) of allowed filtering fields,
         *     (optional) 'sort' => (array) of allowed sorting fields,
         *   ]
         * ]
         */
        return [
            'page' => [
                'table' => 'pages',
                'filter' => ['Title']
            ],
            'product' => [
                'table' => 'products',
                'filter' => [ 'ID', 'Title' ],
                'sort' => ['ID']
            ],
            'category' => [
                'table' => 'product_categories',
                'filter' => ['Title', 'Hierarchy', 'Level']
            ]
        ];
    }


    /** select anything on any (allowed table)
     * + supports filtering and sorting
     * check self::parse_Where_OrderBy() for options;
     * sets a limti of 1000 records
     */
    public static function table($table)
    {
        $sources = self::APITables();
        if (array_key_exists($table, $sources)) {

            $query = Registry::get('REQUEST')->QUERY;

            // Get parametres? => parse filters
            if ($query !== false) {

                $parsed = self::parse_Where_OrderBy(
                    $query,
                    ($sources[$table]['filter'] ?? []),
                    ($sources[$table][ 'sort' ] ?? [])
                );
                $where = $parsed['filter'] ? (' WHERE ' . $parsed['filter']) : '';
                $orderBy = $parsed['sort'] ? (' ORDER BY '. $parsed['sort']) : '' ;
                $bindArguments = $parsed['bind'];

            } else {
                $where = '';
                $orderBy = '';
                $bindArguments = [];
            }

            $db = Registry::use('database');
            $result = $db->runQuery('SELECT *
                FROM '. $sources[$table]['table']
                . $where
                . $orderBy
                .' LIMIT 1000',
                $bindArguments
            );

            reply_json([
                'success' => true,
                'client' => Registry::get('REQUEST')->SIGNATURE,
                'result' => $result
            ]);

        } else {
            reply_json(['success' => false]);
        }
        die();
    }


    /** get record of {table} by ID
     * (if table has no ID then return false)
     */
    public static function record($table, $id)
    {
        $sources = self::APITables();
        if (array_key_exists($table, $sources)) {

            $db = Registry::use('database');
            $result = $db->runQuery("SELECT *
                FROM ". $sources[$table]['table'] ."
                WHERE ID = :id",
                [':id' => $id]
            );
            reply_json([
                'success' => true,
                'data' => $result[0]
            ]);
            
        } else {
            reply_json(['status' => false]);
            
        }
        die();
    }


    /** category_by_url
     * product categorie by full-friendly-URL
     * @param $url (string): full friendly url
     */
    public static function category_by_url($url)
    {
        $category = proxy(
            [\app\models\market\ProductCategories_model::class, 'categoryFromUrl'],
            [ $url ], CACHE_CATEGORY_TTL
        );
        if ($category !== false) {
            reply_json([
                'success' => true,
                'result' => $category
            ]);

        } else {
            reply_json(['status' => false]);
            
        }
        die();
    }

    /** parse Where & OrderBy
     * -------------------------------------------------------------------------
     * parses SAFELY the query string to Where {CONDITIONS} and ORDER BY clauses
     * according to the specified rules.
     * 
     * The rules:
     * ** filters: ?fieldname=[operator]:value &...
     * where operators:[ like | startlike | endlike | eq | gt | gteq | lt |t leq ]
     * 
     * ** Order by: ?_sort=fieldname[:[asc|desc]][,field[,]]
     * 
     * for example: ?id=gt:4&active=1&_sort:reputation:desc,category
     * parses to WHERE id > 4 AND active = 4 ORDER BY reputation desc, catetory asc
     * 
     * -------------------------------------------------------------------------
     * arguments:
     * @param $query (string): string to be parsed
     * @param $filters (array): the allowed fields to apply filters
     * @param $sortings (array): the allowed fields to sort the result
     * @return array('filter'=>(string) , 'sort'=>(string) , 'bind'=>(array))
     */
    private static function parse_Where_OrderBy($query, $filters=[], $sortings=[])
    {
        // break query to [key => value] pairs
        parse_str($query, $queryArray);

        $filterClause = [];     // array to hold filter/WHERE clauses
        $sortClause = [];       // array to hold sort/ORDER-BY caluses
        $bindings = [];         // array to hold variable bindigs 

        // parse filers
        // ---------------------------------------------------------------------
        foreach($queryArray as $filter => $value) {

            if (in_array($filter, $filters)) {

                $parts = explode(':', $value );  // that is => [operator], value
                if (count($parts) == 0) {
                    // forget it

                } else if (count($parts) == 1) {
                    $filterClause[] = "{$filter} = :{$filter}";
                    $bindings[$filter] = $parts[0];

                } else {

                    switch ($parts[0]) {
                        case 'like':
                            $filterClause[] = "{$filter} LIKE :{$filter}";
                            $bindings[':'.$filter] = '%'.$parts[1].'%';
                            break;

                        case 'startlike':
                            $filterClause[] = "{$filter} LIKE :{$filter}";
                            $bindings[':'.$filter] = $parts[1].'%';
                            break;

                        case 'endlike':
                            $filterClause[] = "{$filter} LIKE :{$filter}";
                            $bindings[':'.$filter] = '%'.$parts[1];
                            break;

                        case 'gt':
                            $filterClause[] = "{$filter} > :{$filter}";
                            $bindings[':'.$filter] = $parts[1];
                            break;

                        case 'gteq':
                            $filterClause[] = "{$filter} >= :{$filter}";
                            $bindings[':'.$filter] = $parts[1];
                            break;

                        case 'lt':
                            $filterClause[] = "{$filter} < :{$filter}";
                            $bindings[':'.$filter] = $parts[1];
                            break;

                        case 'lteq':
                            $filterClause[] = "{$filter} <= :{$filter}";
                            $bindings[':'.$filter] = $parts[1];
                            break;
                        
                        case 'eq':
                        default:
                            $filterClause[] = "{$filter} = :{$filter}";
                            $bindings[':'.$filter] = $parts[1];
                    }
                }
            }
        }
        $whereSQL = ($filterClause == [])
            ? false
            : implode(' AND ', $filterClause);


        // parse sort options
        // ---------------------------------------------------------------------
        if (isset($queryArray['_sort'])) {
            $sortTerms = explode(',', $queryArray['_sort']);

            foreach($sortTerms as $term) {

                $parts = explode(':', $term);
                if ($parts != [] && in_array($parts[0], $sortings) ) {
                    $sortClause[] = (count($parts)==1)
                        ? $parts[0]
                        : $parts[0] .' '. (($parts[1] == 'desc') ? 'desc' : 'asc');
                }
            }

        }
        $sortSQL = ($sortClause == [])
            ? false
            : implode(', ', $sortClause);
              
        return ([
            'filter' => $whereSQL,
            'sort' => $sortSQL,
            'bind' => $bindings
        ]);

    }

}