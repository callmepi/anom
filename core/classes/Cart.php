<?php
/** TODO:
 * not implemented yet
 */
class Cart
{

    public function checkout()
    {

    }

    public function addOrder()
    {
        // get cart from cookie
        // VITAL: validate + sanitize
        //
        // then
        //
        // get address and contact details from post
    }


    /** history
     * get the last $limit cart-orders
     * @param $limit (int)
     */
    public function history($limit)
    {
        // SELECT O.* , OP.* , P.*
        // FROM orders O
        // lEFT JOIN order_products OP ON OP.oid = O.id
        // LEFT JOIN products P ON P.id = OP.pid
        // WHERE uid = :uid
        // AND O.id IN (
        //    SELECT id FROM orders WHERE uid = :uid
        //    ORDER BY id DESC
        // )
        //
        // $items = runLimitQuery(sql, [uid = $this->uid], 10)
        // 
        // oranize in results = [ 
        //    { 
        //        oid,
        //        prods: [ 
        //            { 
        //                pid,
        //                prodlabel,
        //                count 
        //            },
        //            ...
        //        ] 
        //    },
        //    ...,
        //    ]
        //
        // return $results

    }
}



/* ---
orders:
 : id
 : uid (user id)

order_products
 : id
 : oid (order id)
 : pid (product id)
 -- */