<?php
$conn = ftp_connect('185.173.111.60');
if(!ftp_login($conn, 'u796027200', '#Omar2811#')){ die("FTP login failed"); }
ftp_pasv($conn, true);

$remote_dir = 'domains/caxiastemturismo.com.br/public_html/';

// Create public_html if needed (optional, just safety)
@ftp_mkdir($conn, 'domains');
@ftp_mkdir($conn, 'domains/caxiastemturismo.com.br');
@ftp_mkdir($conn, 'domains/caxiastemturismo.com.br/public_html');

// Delete default.php from Hostinger
@ftp_delete($conn, $remote_dir . 'default.php');

echo "Uploading ZIP to " . $remote_dir . "site_caxias_hostinger.zip...\n";
if(ftp_put($conn, $remote_dir . 'site_caxias_hostinger.zip', 'site_caxias_hostinger.zip', FTP_BINARY)) {
    echo "ZIP uploaded.\n";
} else {
    echo "ZIP upload failed.\n";
}

echo "Uploading unzip.php...\n";
if(ftp_put($conn, $remote_dir . 'unzip.php', 'unzip.php', FTP_ASCII)) {
    echo "Unzipper uploaded.\n";
}

// Cleanup the root directory from my earlier mistakes
@ftp_delete($conn, 'site_caxias_hostinger.zip');
@ftp_delete($conn, 'unzip.php');

ftp_close($conn);
?>
