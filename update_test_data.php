<?php
// Quick script to update test data for demonstration

$db = mysqli_connect('localhost', 'root', '', 'ppdb_tk');
if (!$db) {
    die('Connection failed: ' . mysqli_connect_error());
}

// Update pendaftaran status to pembayaran_verified
$pendaftaranId = 1;
$query = "UPDATE pendaftaran SET status_pendaftaran = 'pembayaran_verified' WHERE id = $pendaftaranId";

if (mysqli_query($db, $query)) {
    echo "✓ Pendaftaran status updated to 'pembayaran_verified'\n";
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

// Insert test pembayaran data
$pembayaranQuery = "INSERT INTO pembayaran (pendaftaran_id, jumlah, bukti_bayar, status_bayar, tanggal_bayar, created_at, updated_at) 
VALUES ($pendaftaranId, 500000, 'writable/uploads/pembayaran/test_payment.jpg', 'verified', NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE updated_at = NOW()";

if (mysqli_query($db, $pembayaranQuery)) {
    echo "✓ Pembayaran test data created/updated\n";
} else {
    echo "✗ Error: " . mysqli_error($db) . "\n";
}

// Check results
$checkQuery = "SELECT id, nomor_pendaftaran, status_pendaftaran FROM pendaftaran WHERE id = $pendaftaranId";
$result = mysqli_query($db, $checkQuery);
if ($row = mysqli_fetch_assoc($result)) {
    echo "\n✓ Current Status: " . $row['status_pendaftaran'] . "\n";
}

mysqli_close($db);
echo "\nTest data updated successfully!\n";
