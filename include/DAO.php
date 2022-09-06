<?php

require_once 'common.php';

class DAO {

    public function retrieve_username($username) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * FROM account WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $ret_account = null;
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        if ( $row = $stmt->fetch() ) {
            $ret_account =
                new Account(
                    $row['id'],
                    $row['email'],
                    $row['username'],
					$row['pass']
				);
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $ret_account;
    }


    public function retrieve_email($email) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * FROM account WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $ret_account = null;
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        if ( $row = $stmt->fetch() ) {
            $ret_account =
                new Account(
                    $row['id'],
                    $row['email'],
                    $row['username'],
					$row['pass']
				);
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $ret_account;
    }


    public function create($email, $username, $password) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "INSERT INTO account (email, username, pass) VALUES (:email, :username, :pass)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $result = $stmt->execute();
        
        // Step 4 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 5 - Return status of creation
        return $result;
    }


    public function change_password($username, $password) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "UPDATE
                    account
                SET
                    pass = :pass
                WHERE 
                    username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':pass', $password, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $result = $stmt->execute();
        
        // Step 4 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 5 - Return status of change pwd
        return $result;
    }

    public function retrieve_cart($account_id) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * FROM cart WHERE account_id = :account_id";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        
        // Step 3 - Execute SQL Query
        $cart_arr = [];
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        while ( $row = $stmt->fetch() ) {
            $cart_arr[] =
                new Product(
                    $row['id'],
                    $row['p_id'],
                    $row['p_name'],
                    $row['photo'],
                    $row['p_url'],
                    $row['price'],
                    $row['ecommerce']
                );
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $cart_arr;
    }


    public function retrieve_product_cart($account_id, $p_id, $ecom) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * FROM cart WHERE account_id = :account_id AND p_id = :p_id AND ecommerce = :ecom";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':p_id', $p_id, PDO::PARAM_STR);
        $stmt->bindParam(':ecom', $ecom, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $result = null;
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        if ( $row = $stmt->fetch() ) {
            $result = new Product(
                    $row['id'],
                    $row['p_id'],
                    $row['p_name'],
                    $row['photo'],
                    $row['p_url'],
                    $row['price'],
                    $row['ecommerce']
                );
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $result;
    }


    public function add_to_cart($account_id, $p_id, $p_name, $photo, $p_url, $price, $ecom) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "INSERT INTO cart
                    (
                        account_id, 
                        p_id,
                        p_name,
                        photo,
                        p_url,
                        price,
                        ecommerce
                    )
                VALUES
                    (
                        :account_id, 
                        :p_id,
                        :p_name,
                        :photo,
                        :p_url,
                        :price,
                        :ecommerce
                    )";

        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':p_id', $p_id, PDO::PARAM_STR);
        $stmt->bindParam(':p_name', $p_name, PDO::PARAM_STR);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
        $stmt->bindParam(':p_url', $p_url, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':ecommerce', $ecom, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $status = $stmt->execute();
        
        // Step 4 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 5 - Return status of adding to cart
        return $status;
    }


    public function delete_from_cart($id) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "DELETE FROM cart WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Step 3 - Execute SQL Query
        $status = $stmt->execute();
        
        // Step 4 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 5 - Return status of deleting item from cart
        return $status;
    }


    public function retrieve_favourites($account_id) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * FROM favourite WHERE account_id = :account_id";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        
        // Step 3 - Execute SQL Query
        $fav_arr = [];
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        while ( $row = $stmt->fetch() ) {
            $fav_arr[] =
                new Product(
                    $row['id'],
                    $row['p_id'],
                    $row['p_name'],
                    $row['photo'],
                    $row['p_url'],
                    $row['price'],
                    $row['ecommerce']
                );
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $fav_arr;
    }


    public function retrieve_product_fav($account_id, $p_id, $ecom) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT * FROM favourite WHERE account_id = :account_id AND p_id = :p_id AND ecommerce = :ecom";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':p_id', $p_id, PDO::PARAM_STR);
        $stmt->bindParam(':ecom', $ecom, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $result = null;
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        if ( $row = $stmt->fetch() ) {
            $result = new Product(
                    $row['id'],
                    $row['p_id'],
                    $row['p_name'],
                    $row['photo'],
                    $row['p_url'],
                    $row['price'],
                    $row['ecommerce']
                );
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return (if any)
        return $result;
    }


    public function add_to_fav($account_id, $p_id, $p_name, $photo, $p_url, $price, $ecom) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "INSERT INTO favourite
                    (
                        account_id, 
                        p_id,
                        p_name,
                        photo,
                        p_url,
                        price,
                        ecommerce
                    )
                VALUES
                    (
                        :account_id, 
                        :p_id,
                        :p_name,
                        :photo,
                        :p_url,
                        :price,
                        :ecommerce
                    )";

        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        $stmt->bindParam(':p_id', $p_id, PDO::PARAM_STR);
        $stmt->bindParam(':p_name', $p_name, PDO::PARAM_STR);
        $stmt->bindParam(':photo', $photo, PDO::PARAM_STR);
        $stmt->bindParam(':p_url', $p_url, PDO::PARAM_STR);
        $stmt->bindParam(':price', $price, PDO::PARAM_STR);
        $stmt->bindParam(':ecommerce', $ecom, PDO::PARAM_STR);
        
        // Step 3 - Execute SQL Query
        $status = $stmt->execute();
        
        // Step 4 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 5 - Return status of adding item to fav
        return $status;
    }


    public function delete_from_fav($id) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "DELETE FROM favourite WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Step 3 - Execute SQL Query
        $status = $stmt->execute();
        
        // Step 4 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 5 - Return status of deleting item from fav
        return $status;
    }


    public function count_cart($account_id) {
        // Step 1 - Connect to Database
        $connMgr = new ConnectionManager();
        $pdo = $connMgr->getConnection();

        // Step 2 - Write & Prepare SQL Query (take care of Param Binding if necessary)
        $sql = "SELECT count(*) as count FROM cart WHERE account_id = :account_id";
        $stmt = $pdo->prepare($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':account_id', $account_id, PDO::PARAM_INT);
        
        // Step 3 - Execute SQL Query
        $count = 0;
        $stmt->execute();
        
        // Step 4 - Retrieve Query Results (if any)
        if ( $row = $stmt->fetch() ) {
            $count = $row['count'];
        }   
        
        // Step 5 - Clear Resources $stmt, $pdo
        $stmt = null;
        $pdo = null;

        // Step 6 - Return number of items in cart
        return $count;
    }
}
?>