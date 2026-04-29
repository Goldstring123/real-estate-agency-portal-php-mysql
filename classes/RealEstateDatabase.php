<?php
require_once __DIR__ . '/Database.php';

class RealEstateDatabase {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function addUser(string $userName, string $contactInfo, string $passwordHash, string $userType): bool {
        $sql = "INSERT INTO Users (userName, contactInfo, passwordHash, userType)
                VALUES (:userName, :contactInfo, :passwordHash, :userType)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userName' => $userName,
            ':contactInfo' => $contactInfo,
            ':passwordHash' => $passwordHash,
            ':userType' => $userType
        ]);
    }

    public function getUserByUsername(string $userName) {
        $sql = "SELECT * FROM Users WHERE userName = :userName LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userName' => $userName]);
        return $stmt->fetch();
    }

    public function addProperty(string $title, string $propertyType, string $address, string $city, float $price, string $status, int $agentId): bool {
        $sql = "INSERT INTO Properties (title, propertyType, address, city, price, status, agentId)
                VALUES (:title, :propertyType, :address, :city, :price, :status, :agentId)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':title' => $title,
            ':propertyType' => $propertyType,
            ':address' => $address,
            ':city' => $city,
            ':price' => $price,
            ':status' => $status,
            ':agentId' => $agentId
        ]);
    }

    public function getAllProperties(): array {
        $sql = "SELECT p.*, u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                ORDER BY p.propertyId DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll();
    }

    public function getPropertyById(int $propertyId) {
        $sql = "SELECT p.*, u.userName AS agentName, u.contactInfo AS agentContact
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                WHERE p.propertyId = :propertyId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':propertyId' => $propertyId]);
        return $stmt->fetch();
    }

    public function addInquiry(int $userId, int $propertyId, string $message): bool {
        $sql = "INSERT INTO Inquiries (userId, propertyId, message, inquiryDate)
                VALUES (:userId, :propertyId, :message, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':userId' => $userId,
            ':propertyId' => $propertyId,
            ':message' => $message
        ]);
    }

    public function getUserDetails(int $userId) {
        $sql = "SELECT * FROM Users WHERE userId = :userId";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':userId' => $userId]);
        $user = $stmt->fetch();
        
        if (!$user) return null;
        
        $sqlInquiries = "SELECT i.*, p.title as propertyTitle 
                        FROM Inquiries i
                        JOIN Properties p ON i.propertyId = p.propertyId
                        WHERE i.userId = :userId";
        $stmtInquiries = $this->conn->prepare($sqlInquiries);
        $stmtInquiries->execute([':userId' => $userId]);
        $user['inquiries'] = $stmtInquiries->fetchAll();
        
        $sqlFavorites = "SELECT f.*, p.title as propertyTitle, p.price, p.city
                        FROM Favorites f
                        JOIN Properties p ON f.propertyId = p.propertyId
                        WHERE f.userId = :userId";
        $stmtFavorites = $this->conn->prepare($sqlFavorites);
        $stmtFavorites->execute([':userId' => $userId]);
        $user['favorites'] = $stmtFavorites->fetchAll();
        
        $sqlTransactions = "SELECT t.*, p.title as propertyTitle
                           FROM Transactions t
                           JOIN Properties p ON t.propertyId = p.propertyId
                           WHERE t.userId = :userId";
        $stmtTransactions = $this->conn->prepare($sqlTransactions);
        $stmtTransactions->execute([':userId' => $userId]);
        $user['transactions'] = $stmtTransactions->fetchAll();
        
        return $user;
    }

    public function getPropertiesByCity(string $city): array {
        $sql = "SELECT p.*, u.userName AS agentName
                FROM Properties p
                JOIN Users u ON p.agentId = u.userId
                WHERE p.city LIKE :city";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':city' => '%' . $city . '%']);
        return $stmt->fetchAll();
    }
}
?>