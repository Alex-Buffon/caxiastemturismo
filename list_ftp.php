<?php
$conn = ftp_connect('185.173.111.60');
ftp_login($conn, 'u796027200', '#Omar2811#');
ftp_pasv($conn, true);
$files = ftp_nlist($conn, '.');
print_r($files);
ftp_close($conn);
?>
