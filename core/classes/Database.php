<?php
/** Database Class
 * 
 */
namespace anom\core;

class Database {

    /** PROPERTIES
     * -------------------------------------------------------------------------
     */

    private $throw_errors;

    private $connection = null;

    private $result = null;

    private $rowCount;


    /** METHODS
     * -------------------------------------------------------------------------
     */

    /** __construct
     *
     * @param $errors: Set to true, to catch error exceptions.
     * @return void
     */
    public function __construct($errors = false)
    {
        $this->throw_errors = PRODUCTION ? false : true;

        if (null == $this->connection) {
            try {
                    $this->connection = new PDO(
                        "mysql:" . PDO_HOST . ";" . "dbname=" . DB_NAME,
                        DB_USER,
                        DB_PASS,
                        array(
                            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                            PDO::MYSQL_ATTR_LOCAL_INFILE => true
                        )
                    );

                    // handle error reporting
                    if ($this->throw_errors) {
                        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    }
                    $this->setTimezone();

            } catch (PDOException $exc) { handle_exception($exc); }
        }
        # return $this->connection;
    }


    /** disconnect
     * CHECK: not sure if needed
     */
    public function disconnect()
    {
        $this->connection = null;
    }


    /** set Timezone
     * (Self-explanatory)
     */
    public function setTimezone($timezone = DB_TIMEZONE) {
        // $this->connection->prepare($timezone)->execute();
    }


    /** lastInsertID
     * returns the ID of last inserted record
     */
    public function lastInsertID()
    {
        return $this->connection->lastInsertId();
    }



    /** rowCount
     * 
     * returns the number of affected rows from the last modification query
     * (that inludes any of INSERT, UPDATE or DELETE; not the SELECT ones)
     * 
     * NOTE:
     * originally in php PDO driver, rowCount is applied onto the stamement;
     * here it is applied onto the actual object, making the call much easier
     * 
     * @param void;
     * @return (int) : number of affected rows
     */
    public function rowCount()
    {
        return $this->rowCount;
    }


    /** query
     * ---
     * set and execute a query safely;
     * save results as associative array; DO NOT RETURN RESULTS
     * 
     * @param $sql (string): SQL query
     * @param $args (array): array of values to bind into SQL
     * @param $pypass (boolean): flag to bypass security check
     * 
     * @return $this (database handler)
     */
    public function query($sql, $args=[])
    {
        try {

            $stmt = $this->connection->prepare($sql);

            if ($args == []) {
                $result = $stmt->execute();
            
            } else {
                $result = $stmt->execute($args);
            
            }

            // keep rowCount (valid for INSERT, UPDATE, DELETE)
            $this->rowCount = $stmt->rowCount();

            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->result = $result;

            return $this;

        } catch (PDOException $exc) { handle_exception($exc); }

    }


    /** getAll
     * ---
     * return all resulted records
     * use it after db->query();
     */
    public function getAll()
    {
        return ($this->result === null) ? false : $this->result;
    }


    /** getFirst
     * ---
     * get first row of the resulted query;
     * used when one row is expected
     * ex. $db->query('SELECT * FROM users WHERE id = :id',['id'=>1])->getFirst();
     */
    public function getFirst()
    {
        if (($this->result === null) || ($this->result == [])) {
            return false;

        } else { return $this->result[0]; }
    }


    /** getOnly
     * ---
     * return the first column value of the first row
     * used when only one value is needed
     * ex. $db->query('SELECT Count(id) FROM table',[])->getOnly();
     */
    public function getOnly()
    {
        if (($this->result === null) || ($this->result == [])) {
            return false;
        
        } else { return array_values($this->result[0])[0]; }
    }


    /** runQuery
     * --- (shortcut method)
     * execute a query safely;
     * return results as associative array
     * @param $sql (string): SQL query
     * @param $args (array): array of values to bind into SQL
     * @param $pypass (boolean): flag to bypass security check
     */
    public function runQuery($sql, $args=[])
    {
        return $this->query($sql, $args)->getAll();
    }


    /** runLimitQuery( sql, args, limit=100, offset = null )
     * set LIMIT / OFFSET clauses in a secure way
     * @param $sql (string): SQL query
     * @param $args (array): array of values to bind into SQL
     * @param $limit  (int): LIMIT number
     * @param $offset (int): OFFSET number
     */
    public function runLimitQuery($sql, $args, $limit = 100, $offset = null)
    {
        $limitStr = $offsetStr = "";

        // construct LIMIT clause
        if (is_int($limit)) {
            $limitStr = " LIMIT {$limit}";

            // construct OFFSET clause (when a LIMIT pre-exists)
            if (is_int($offset)) {
                $offsetStr =" OFFSET {$offset}";
            }
        }

        $sql = $sql . $limitStr . $offsetStr;

        return $this->runQuery($sql, $args);
    }


    /** insert
     * @param $table (string): name of table
     * @param $values: an associative of (fieldName => value) pairs
     * 
     * example call:
     * ---
     * $db->insert('products',
     *    [
     *      'title' => 'My Dark Chocolate 200g',
     *      'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit ...',
     *      'isFood' => 1,
     *      'isToxic' => 0 
     *    ]
     * );
     * 
     * ...which prepares the SQL query:
     * INSERT INTO products (title, text, isFood, isToxic)
     * VALUES (:title, :text, :isFood, :isToxic)
     * 
     * ...and injects the values: [ :title => 'My Dark Chocolate 200g' , ... ]
     */
    public function insert($table, array $values)
    {
        $fieldSets = [];
        $valueSets = [];
        $bindSets = [];

        foreach($values as $key => $val) {
            $fieldSets[] = $key; 
            $valueSets[] =':'. $key;
            $bindSets[':'. $key] = $val;
        }

        $sql = "INSERT INTO {$table} (". implode(', ', $fieldSets) .") 
                VALUES (". implode(', ', $valueSets) .")";
        
        return $this->runQuery($sql, $bindSets);
    }


    /** update
     * @param $table (string): name of table
     * @param $values: an associative of (fieldName => value) pairs
     * @param $id: an associative of (fieldName => value) index fields
     * 
     * example call:
     * ---
     * $db->update('products',
     *    [ 'title' => 'My Chocolate','isFood' => 1 ],
     *    [ 'id' => 123 ]
     * );
     * 
     * ...which prepares the SQL query:
     * UPDATE products SET `title` = :title, `isFood` = :isFood WHERE id = :id
     * 
     * ...and injects: [':title'=> 'My Chocolate' , ':isFood'=> 1 , ':id'=> 123]
     */
    public function update( $table, array $values, array $identity)
    {   
        $fieldSets = [];    // array of field names
        $idSets = [];       // array of data-holders
        $bindSets = [];     // array of data-bindings

        foreach($values as $key => $val) {
            $fieldSets[] = "{$key} = :{$key}";
            $bindSets[':'. $key] = $val;
        }

        foreach($identity as $key => $val) {
            $idSets = "{$key} = :{$key}";
            $bindSets[':'. $key] = $val;
        }

        $sql = "UPDATE {$table} SET ". implode(', ', $fieldSets)
                ." WHERE ". implode(" AND ", $idSets);

        return $this->runQuery($sql, $bindsArray);
    }

    
    /** multiInsert( table, fields , values )
     * Construct a multiple-insert clause
     * 
     * @param $table (string): name of table
     * @param $fields (array): array with field-names
     * @param $values (array): array of value-arrays 
     *
     * example call:
     * ---
     * $db->multiInsert('order_products',
     *  [ 'orderID', 'productID', 'unitPrice', 'quantity', 'note' ],
     *  [
     *      [ 124, 102030, 1.25, 5, '' ],
     *      [ 124, 102040, 10.50, 2, 'some note about product #102040' ],
     *      [ 124, 102050, 7.20, 3, '' ],
     *      [ 124, 102060, 3.25, 1, 'some other note' ]
     *  ]
     * );
     */   
    public function multiInsert($table, array $fieldsArray, array $valuesArray)
    {
        if (count($fieldsArray) != count($valuesArray[0])) {
            throw new Exception('Fields and value arrays don\'t match.');
        }

        // setup fieldsSet 
        // ex. "(Title, Price, Status)"
        $fieldsSet = ' (`'. implode(
            '`, `',     // make sure fieldnames are not SQL-bound terms
            str_replace('`', '', $fieldsArray)      // clean fieldnames
        ) .'`) ';

        // setup holders array and bind-values array
        // ex. "(:Title1, :Price1, :Status1), (:Title2, :Price2, :Status2), ...",
        $holdersArray = [];
        $bindsArray = [];
        $counter = 1;
        foreach($valuesArray as $key => $rowArray) {
            $rowHolders = [];

            foreach($itemArray as $key => $val) {
                $rowHolders = ':'. $fieldsArray[$key] . $counter;
                $bindsArray[ ':'. $fieldsArray[$key] . $counter ] = $val;
            }
            $holdersArray[] = '('. implode(', ', $rowHolders ) .')';
            $counter++;
        }

        $sql = "INSERT INTO {$table}" . $fieldsSet
            . ' VALUES '. impload(', ', $holdersArray);

        return $this->runQuery($sql, $bindsArray);
    }

}
