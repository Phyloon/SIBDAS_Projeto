<?php
// 1. Initialize variables to 0 IMMEDIATELY. 
// This fixes the 'Undefined variable' error permanently.
$total = 0;
$located = 0;
$missing = 0;
$unconfirmed = 0;

try {
    // 2. Ensure $pdo exists from your header
    if (isset($pdo)) {
        $query = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN location_wing IS NOT NULL AND location_room IS NOT NULL THEN 1 ELSE 0 END) as located,
            SUM(CASE WHEN location_wing IS NULL OR location_room IS NULL THEN 1 ELSE 0 END) as missing 
            FROM equipamentos";
        
        $stmt = $pdo->query($query);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // 3. Assign values if the query returned data
        if ($stats) {
            $total   = $stats['total'] ?? 0;
            $located = $stats['located'] ?? 0;
            $missing = $stats['missing'] ?? 0;
        }
    }
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    // Variables stay 0 because of the initialization at the top
}
?>