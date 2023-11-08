<?php

class ExpenseController
{
    public function test()
    {
        http_response_code(400);
        echo json_encode(['Welcome to my API']);
    }

    public function index()
    {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM product_tbl");
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($products);
        } catch (PDOException $error) { // Catch exceptions of type PDOException
            echo json_encode(["error" => "Something Wrong", "desc" => $error->getMessage()]);
        }
    }


    public function store()
    {
        global $pdo;
        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['name']) || !isset($data['category']) || !isset($data['price']) || !isset($data['quantity'])) {
            echo json_encode(['error' => 'Please complete all the fields']);
        } else {
            $stmt = $pdo->prepare("INSERT INTO product_tbl (name, category, price, quantity) 
                                VALUES (?, ?, ?, ?)");
            $stmt->execute([$data["name"], $data["category"], $data["price"], $data["quantity"]]);
            echo json_encode(["message" => "Expenses added successfully"]);
        }
    }

    public function getSales()
    {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM sales_tbl");
            $sales = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($sales);
        } catch (PDOException $error) { // Catch exceptions of type PDOException
            echo json_encode(["error" => "Something Wrong", "desc" => $error->getMessage()]);
        }
    }

    public function outgoing()
    {
        global $pdo;

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data["product_id"]) || !isset($data["quantity"]) || !isset($data["total_amount"])) {
            echo json_encode(['error' => 'Please complete all the fields']);
        } else {
            // Insert sales in sales_tbl
            $stmt = $pdo->prepare("INSERT INTO sales_tbl (product_id, quantity, total_amount) VALUES (?, ?, ?)");
            $stmt->execute([$data["product_id"], $data["quantity"], $data["total_amount"]]);

            // Select the product information of the sales product id
            $stmt = $pdo->prepare("SELECT * FROM product_tbl WHERE id = ?");
            $stmt->execute([$data["product_id"]]); // Wrap in an array

            $product = $stmt->fetch(PDO::FETCH_ASSOC); // Use fetch() to get a single row

            // Calculate the updated quantity and price of product
            $deductedQuantity = $product["quantity"] - $data["quantity"];
            $deductedPrice = $product["price"] - ($data["quantity"] * $data["total_amount"]);

            // Prepare and execute the UPDATE statement for product_tbl
            $stmt = $pdo->prepare("UPDATE product_tbl SET price = ?, quantity = ? WHERE id = ?");
            $stmt->execute([$deductedPrice, $deductedQuantity, $data["product_id"]]);

            echo json_encode(["message" => "Sales added"]);
        }
    }



    public function show($params)
    {
        global $pdo;

        // Check if 'id' is present in the parameters
        if (!isset($params['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            return;
        }

        $id = $params['id'];

        // Validate that $id is a positive integer
        if (!ctype_digit((string)$id) || $id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid expense ID']);
            return;
        }

        $stmt = $pdo->prepare("SELECT * FROM products_tbl WHERE id = ?");
        $stmt->execute([$id]);

        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$expense) {
            http_response_code(404);
            echo json_encode(['error' => 'Expense not found']);
            return;
        }

        echo json_encode($expense);
    }

    public function destroy($params)
    {
        global $pdo;

        // Check if 'id' is present in the parameters
        if (!isset($params['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request']);
            return;
        }

        $id = $params['id'];

        // Validate that $id is a positive integer
        if (!ctype_digit((string)$id) || $id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid expense ID']);
            return;
        }

        $stmt = $pdo->prepare("SELECT * FROM product_tbl WHERE id = ?");
        $stmt->execute([$id]);

        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$expense) {
            http_response_code(404);
            echo json_encode(['error' => 'Expense not found']);
            return;
        }

        $stmt = $pdo->prepare("DELETE FROM product_tbl WHERE id = ?");
        $stmt->execute([$id]);

        $expense = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(['message' => "Successfully deleted."]);
    }
}
