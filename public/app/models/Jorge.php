<?php

namespace app\models;

use \Registry;

/** Jorge
 * a database reference and adminitration toolkit
 * ---
 * The name Jorge comes from Uberto Eco's book: 'il nome della rosa',
 * (Jorge is the blind monk who supervises the monastery's library ;)
 */
class Jorge
{

    /** tables
     * array to keep all database-tables' critical information.
     * @key create (array) includes all 'CREATE TABLE' statements
     * @key relate (array) includes relations between tables
     * @key fields (array) includes all important fields (when no select*)
     * @key filter (array) includes all fields used to filter results
     */
    private $tables = [
        'create' => [],
        'relate' => [],
        'fields' => []
    ];

    public function __construct()
    {
        $db = Registry::use('database');

        // get all tables
        $tableList = $db->runQuery("SHOW TABLES", []);
        
        // get all CREATE TABLE statements
        foreach($tableList as $tableArray) {
            $table = array_shift($tableArray);
            $create = $db->runQuery("SHOW CREATE TABLE {$table}", []);
            $this->tables['create'][$table] = $create[0]['Create Table'];
        }

        // define relations
        $this->tables['relate'] = [

            'lesson' => [
                'course' => 'course.id = lesson.course_id',
                'lesson_media' => 'lesson_media.lesson_id = lesson.id',
                'lesson_privilege' => 'lesson_privilege.lesson_id = lesson.id'
            ],

            'page' => [
                'page_media' => 'page_media.page_id = page.id'
            ],

            'user' => [
                'user_privilege' => 'user_privilege.user_id = user.id'
            ],

            'privilege' => [
                'user_privilege' => 'user_privilege.privilege_id = privilege.id'
            ],

        ];

        $this->extractTableFields();

        // return true;
    }


    /** Create...
     * one or more database tables
     * ---
     * @param $datasource (string|void) : table name or none
     * if none then all tables will be created
     * @return (boolean)
     * 
     * NOTE:
     * for safety reasons this method is not executing SQL;
     * it just echoes the SQL that needs to be executed in 
     * order to crate all database tables.
     */
    public function create($datasource = '')
    {
        // if no datasource passed then datasource is all tables
        if ($datasource == '') {
            $datasource = array_keys(self::$tables['create']);
        
        } else {
            // if datasource exist, make it an array
            if (array_key_exists($datasource, self::$tables['create'])) {
                $datasource = [ $datasource ];

            } else {
                // table does not exist
                return fasle;
            }
        }

        $sql = "";
        foreach( $datasource as $table ) {          
            $sql .= "-- CREATE ". $table .";\n". self::$tables['create'][$table] . "\n\n";
        }

        return ['sql' => $sql];
    }


    /** Calculate fields of every table
     * ---
     * Parse every CREATE SQL statement and extract list of fields;
     * Algorithm implements linear-parsing (faster than a recursive one)
     */
    private function extractTableFields()
    {
        // words used by SQL that can not be field names
        $nonFields = ['PRIMARY', 'KEY', '(', ')', 'CONSTRAINT', 'UNIQUE'];    // reduced list (used on CREATE)

        foreach( $this->tables['create'] as $table => $sqlCreate ) {

            $fields = [];   // variable to hold the extracted fields

            // get text between first open-parentesis and last close-parentesis
            // this is waht lies between 'CREATE TABLE table(' and ') [ENGINE whatever]'
            // (including the patenteses)
            preg_match_all(
                "/\((((?>[^()]+)|(?R))*)\)/",
                str_replace("\n", '', $sqlCreate),
                $match
            );
            // remove 1st+last parentesis
            $mainDefinitions = substr($match[0][0], 1, -1);

            // replace comma (,) on sub-parenthesis
            // ex: 'decimal(18, 2)' turns to 'decimal(18: 2)'
            // or: 'PRIMARY KEY (id1, id2)' turns to 'PRIMARY KEY (id1: id2)'
            $sentences = preg_replace(
                '/\\(([0-9a-zA-Z_`]*)[ ,]([0-9a-zA-Z_` ]*)\\)/',
                '($1:$2)',
                $mainDefinitions,
                -1
            );

            // devide the banth of sentences to field definitions
            $defines = explode(',', $sentences);

            // now each denine holds a full field definition
            // like: `ID` int(11) NOT NULL AUTO_INCREMENT

            foreach($defines as $def) {
                // check the first term of each sentence
                $terms = explode(' ', trim($def,));
                $term = array_shift($terms);
                
                if (!in_array($term, $nonFields)) {
                    $fields[ str_replace('`', '', $term) ] = implode(' ', $terms);
                }
            }

            $this->tables['fields'][$table] = $fields;
        }

        return;
    }

    public function databaseDocumentation()
    {
        return $this->tables['fields'];
    }
    
}