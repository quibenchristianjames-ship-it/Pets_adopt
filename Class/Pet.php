<?php
class Pet {
    private $conn;
    private $table_name = "pets";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Fetch posts belonging only to a specific user ID
    public function getByUser($user_id) {
        $query = "SELECT id, name, type, breed, age, city, image, user_id FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add a new pet linked to a user ID
    public function createPet($name, $type, $breed, $age, $city, $image, $user_id) {
        $query = "INSERT INTO " . $this->table_name . " (name, type, breed, age, city, image, user_id) VALUES (:name, :type, :breed, :age, :city, :image, :user_id)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":breed", $breed);
        $stmt->bindParam(":age", $age);
        $stmt->bindParam(":city", $city);
        $stmt->bindParam(":image", $image);
        $stmt->bindParam(":user_id", $user_id);

        return $stmt->execute();
    }
}
?>