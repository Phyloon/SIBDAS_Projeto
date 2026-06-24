<?php
// Initialize all variables to 0 to avoid "undefined variable" errors
$total = 0;
$located = 0;
$missing = 0;
$unconfirmed = 0; // Not used in query, but kept for consistency

try {
    // Check if the database connection ($pdo) exists (should be loaded by header)
    if (isset($pdo)) {
        // Build query: count total, located (has wing and room), missing (lacks either)
        $query = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN location_wing IS NOT NULL AND location_room IS NOT NULL THEN 1 ELSE 0 END) as located,
            SUM(CASE WHEN location_wing IS NULL OR location_room IS NULL THEN 1 ELSE 0 END) as missing 
            FROM equipamentos";
        
        // Execute and fetch stats as associative array
        $stmt = $pdo->query($query);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // If we got data, assign the values (with fallback to 0)
        if ($stats) {
            $total   = $stats['total'] ?? 0;
            $located = $stats['located'] ?? 0;
            $missing = $stats['missing'] ?? 0;
        }
    }
} catch (PDOException $e) {
    // Log any database error, but keep variables as 0 (already set)
    error_log("DB Error: " . $e->getMessage());
}
?>