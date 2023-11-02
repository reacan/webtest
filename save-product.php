<?php
include('db_dpo.php');

abstract class Product {
    protected $SKU;
    protected $name;
    protected $price;
    protected $productType;

    abstract public function saveToDatabase($conn);
}

class DVDProduct extends Product {
    private $SizeMb;

    public function __construct($SKU, $name, $price, $SizeMb) {
        $this->SKU = $SKU;
        $this->name = $name;
        $this->price = $price;
        $this->productType = 'DVD';
        $this->SizeMb = $SizeMb;
    }

    public function saveToDatabase($conn) {
    
 
 
       // Validate numeric input
       if (!is_numeric($this->price) || floatval($this->price) <= 0 || 
       !is_numeric($this->SizeMb) || floatval($this->SizeMb) <= 0) {
       return $conn->error;
       }
    
    
        // Sanitize
        
        $this->SKU = mysqli_real_escape_string($conn, $this->SKU);
        $this->name = mysqli_real_escape_string($conn, $this->name);
        $this->price = floatval($this->price);
        $this->SizeMb = floatval($this->SizeMb);
        
        // Construct SQL query
        $sqlProducts = "INSERT INTO products (SKU, name, price) VALUES ('$this->SKU', '$this->name', $this->price)";

        if ($conn->query($sqlProducts) !== TRUE) {
        return $conn->error;
        }

        $product_id = $conn->insert_id;

        // Construct SQL query for specific attributes
        $sqlSpecificAttributes = "INSERT INTO DVD (product_id, SizeMb) VALUES ('$product_id', '$this->SizeMb')";

        if ($conn->query($sqlSpecificAttributes) === TRUE) {
            return true;
        } else {
            return $conn->error;
        }
    }
}

class BookProduct extends Product {
    private $WeightKg;

    public function __construct($SKU, $name, $price, $WeightKg) {
        $this->SKU = $SKU;
        $this->name = $name;
        $this->price = $price;
        $this->productType = 'book';
        $this->WeightKg = $WeightKg;
    }

    public function saveToDatabase($conn) {
    
       // Validate numeric input
       if (!is_numeric($this->price) || floatval($this->price) <= 0 || 
       !is_numeric($this->WeightKg) || floatval($this->WeightKg) <= 0) {
       return $conn->error;
       }
    

        // Sanitize
        $this->SKU = mysqli_real_escape_string($conn, $this->SKU);
        $this->name = mysqli_real_escape_string($conn, $this->name);
        $this->price = floatval($this->price);
        $this->WeightKg = floatval($this->WeightKg);

        // Construct SQL query
        $sqlProducts = "INSERT INTO products (SKU, name, price) VALUES ('$this->SKU', '$this->name', $this->price)";

        if ($conn->query($sqlProducts) !== TRUE) {
        return "Error: Product information could not be added to the database. Details: " . $conn->error;
            return $conn->error;
        }

        $product_id = $conn->insert_id;

        // Construct SQL query for specific attributes
        $sqlSpecificAttributes = "INSERT INTO book (product_id, WeightKg) VALUES ('$product_id', '$this->WeightKg')";

        if ($conn->query($sqlSpecificAttributes) === TRUE) {
            return true;
        } else {
          return $conn->error;
        }
    }
}

class FurnitureProduct extends Product {
    private $HeightCm;
    private $WidthCm;
    private $LengthCm;

    public function __construct($SKU, $name, $price, $HeightCm, $WidthCm, $LengthCm) {
        $this->SKU = $SKU;
        $this->name = $name;
        $this->price = $price;
        $this->productType = 'furniture';
        $this->HeightCm = $HeightCm;
        $this->WidthCm = $WidthCm;
        $this->LengthCm = $LengthCm;
    }

    public function saveToDatabase($conn) {
    
        // Validate numeric input
       if (!is_numeric($this->price) || $this->price <= 0 || 
       !is_numeric($this->HeightCm) || $this->HeightCm <= 0 || 
       !is_numeric($this->WidthCm) || $this->WidthCm <= 0 || 
       !is_numeric($this->LengthCm) || $this->LengthCm <= 0) {
       return $conn->error;
   }
    
        // Sanitize
        $this->SKU = mysqli_real_escape_string($conn, $this->SKU);
        $this->name = mysqli_real_escape_string($conn, $this->name);
        $this->price = floatval($this->price);
        $this->HeightCm = floatval($this->HeightCm);
        $this->WidthCm = floatval($this->WidthCm);
        $this->LengthCm = floatval($this->LengthCm);

        // Construct SQL query
        $sqlProducts = "INSERT INTO products (SKU, name, price) VALUES ('$this->SKU', '$this->name', $this->price)";

        if ($conn->query($sqlProducts) !== TRUE) {
     //   return "Error: Product information could not be added to the database. Details: " . $conn->error;
        return $conn->error;
         }

        $product_id = $conn->insert_id;

        // Construct SQL query for specific attributes
        $sqlSpecificAttributes = "INSERT INTO furniture (product_id, HeightCm, WidthCm, LengthCm) VALUES ('$product_id', '$this->HeightCm', '$this->WidthCm', '$this->LengthCm')";

        if ($conn->query($sqlSpecificAttributes) === TRUE) {
            return true;
        } else {
            return $conn->error;
        }
    }
}

//Some additional checks and then posting the data to server

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $SKU = mysqli_real_escape_string($conn, $_POST["SKU"]);
    $name = $_POST["name"];
    $price = $_POST["price"];
    $productType = $_POST["productType"];
    

    // Check for empty fields
    if (empty($SKU) || empty($name) || empty($price) || empty($productType)) {
        echo "Error! ";
        exit();
        /* return "Error: Product information could not be added to the database. Details: " . $conn->error;
           return $conn->error; */
       } 


    // Check if SKU already exists in the database
    $checkQuery = "SELECT * FROM products WHERE SKU = '$SKU'";
    $checkResult = $conn->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        // SKU is not unique
        echo "Error: SKU must be unique!";
        exit();
    }


    // Create a new Product object based on product type
    switch ($productType) {
        case 'DVD':
            $SizeMb = $_POST["SizeMb"];
            $product = new DVDProduct($SKU, $name, $price, $SizeMb);
            break;
        case 'book':
            $WeightKg = $_POST["WeightKg"];
            $product = new BookProduct($SKU, $name, $price, $WeightKg);
            break;
        case 'furniture':
            $HeightCm = $_POST["HeightCm"];
            $WidthCm = $_POST["WidthCm"];
            $LengthCm = $_POST["LengthCm"];
            $product = new FurnitureProduct($SKU, $name, $price, $HeightCm, $WidthCm, $LengthCm);
            break;
        default:
            echo "Invalid product type.";
            exit();
    
    }

    // Insert product into the database
    $result = $product->saveToDatabase($conn);

    if ($result === true) {
        echo "New product added successfully!";
    } else {
             echo "Product could not be added to database!";
    }
}

$conn->close();
?>