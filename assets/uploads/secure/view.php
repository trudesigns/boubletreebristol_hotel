<?php
session_start();

//exit("you do not have permission to view this file");

$filepath = $_SERVER['REQUEST_URI'];
$fullpath = $_SERVER['DOCUMENT_ROOT'].$filepath;
$basename = basename($fullpath);

$nameparts = explode(".",$basename);
$ext = array_pop($nameparts);

//allowed extensions
$mime_types = array(
"avi" => "video/x-msvideo",
"bmp" => "image/bmp",
"css" => "text/css",
"doc" => "application/msword",
"docx" => "application/msword",
"dot" => "application/msword",
"dvi" => "application/x-dvi",
"eps" => "application/postscript",
"exe" => "application/octet-stream",
"gif" => "image/gif",
"gz" => "application/x-gzip",
"htm" => "text/html",
"html" => "text/html",
"ico" => "image/x-icon",
"jpe" => "image/jpeg",
"jpeg" => "image/jpeg",
"jpg" => "image/jpeg",
"js" => "application/x-javascript",
"mdb" => "application/x-msaccess",
"mid" => "audio/mid",
"mny" => "application/x-msmoney",
"mov" => "video/quicktime",
"movie" => "video/x-sgi-movie",
"mp2" => "video/mpeg",
"mp3" => "audio/mpeg",
"mpa" => "video/mpeg",
"mpe" => "video/mpeg",
"mpeg" => "video/mpeg",
"mpg" => "video/mpeg",
"mpp" => "application/vnd.ms-project",
"mpv2" => "video/mpeg",
"ms" => "application/x-troff-ms",
"mvb" => "application/x-msmediaview",
"pbm" => "image/x-portable-bitmap",
"pdf" => "application/pdf",
"pot" => "application/vnd.ms-powerpoint",
"ppm" => "image/x-portable-pixmap",
"pps" => "application/vnd.ms-powerpoint",
"ppt" => "application/vnd.ms-powerpoint",
"pptx" => "application/vnd.ms-powerpoint",
"pub" => "application/x-mspublisher",
"qt" => "video/quicktime",
"ra" => "audio/x-pn-realaudio",
"ram" => "audio/x-pn-realaudio",
"rtf" => "application/rtf",
"rtx" => "text/richtext",
"svg" => "image/svg+xml",
"tgz" => "application/x-compressed",
"tif" => "image/tiff",
"tiff" => "image/tiff",
"trm" => "application/x-msterminal",
"tsv" => "text/tab-separated-values",
"txt" => "text/plain",
"uls" => "text/iuls",
"vcf" => "text/x-vcard",
"wav" => "audio/x-wav",
"xls" => "application/vnd.ms-excel",
"xlsx" => "application/vnd.ms-excel",
"xlt" => "application/vnd.ms-excel",
"zip" => "application/zip"
);

if(!array_key_exists( $ext, $mime_types))
{
	exit("ERROR. invalid extension type");
}

if (file_exists($fullpath)) {
    header('Content-Type: '. $mime_types[$ext]);
    header('Content-Disposition: inline; filename='.$basename);
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($fullpath));
    readfile($fullpath);
    exit();
}
else
{
	exit($filepath."<br>\n".$fullpath."<h1>404  - File not found?!</h1>");	
}

?>	
