<?php
include('../php/db.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT * FROM goal WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $goal = $result->fetch_assoc();
            echo json_encode($goal);
        } else {
            echo json_encode(['error' => 'Goal not found']);
        }

        $stmt->close(); // Close the prepared statement
    } else {
        echo json_encode(['error' => 'Failed to prepare the statement']);
    }
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
