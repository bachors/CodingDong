<?php

/***********************************************************************************
CodingDong v000001 is a code editor, code execute and code tester build with codemirror.
Write css code, html, javascript, PHP, sql and run the code.
Supports cdn sourche. You can try all the code directly on the website.
Try it out!

* Coded by Ican Bachors 2016.
* http://ibacor.com/labs/codingdong
* Updates will be posted to this site.
***********************************************************************************/


error_reporting(0);

## jika tombol preview di pencet maka akan menjalankan perintah di bawah ini ##
if (!empty($_POST['preview'])) {	

	## jika tombol preview == Execute maka akan menjalankan code PHP ##
    if ($_POST['preview'] == "Execute") {
        if (!empty($_POST['code'])) {
            $code = $_POST['code'];
            eval("?> $code ");
        }
    } 

	## atau jika tombol preview == Preview maka akan menjalankan code HTML, CSS dan JS ##
	else if ($_POST['preview'] == "Preview") {
		
		## Menyatukan code CSS, HTML dan JS dalam satu file
        $code = '';
		
		## Jika inputan CDN tidak kosong maka
        if (!empty($_POST['cdn_url'])) {
            $cdn = $_POST['cdn_url'];
			
			# Mendapatkan semua url CDN
			foreach($cdn as $cdn_url) {
				
				## Mengecek file extension di url CDN.
				if(parseurl($cdn_url) == 'css'){
					
					## Jika file extension CDN == css maka
					$code .= '<link rel="stylesheet" href="'.$cdn_url.'">';
				}
				else if(parseurl($cdn_url) == 'js'){
					
					## Jika file extension CDN == js maka
					$code .= '<script type="text/javascript" src="'.$cdn_url.'"></script>';
				}
			}
        }
		
		## Memasukan code CSS yang ada di textarea kedalam <style> {CSS Code} </style> diatas code HTML
        if (!empty($_POST['css'])) {
            $code .= '<style>' . $_POST['css'] . '</style>';
        }
		
		## Memasukan code HTML yang ada di textarea dibawah code CSS dan diatas code JS
        if (!empty($_POST['html'])) {
            $code .= $_POST['html'];
        }
		
		## Memasukan code JS yang ada di textarea kedalam <script> {JS Code} </script> dibawah code CSS dan HTML
        if (!empty($_POST['js'])) {
            $code .= '<script>' . $_POST['js'] . '</script>';
        }
		
		## Menampilkan code CSS, HTML dan JS yang sudah di satukan
        echo $code;
    } 

	## atau jika tombol preview == Go maka akan menjalankan syntax MySQL ##
	else if ($_POST['preview'] == "Go") {
		
		## Inputan host dan username tidak boleh kosong.
        if (!empty($_POST['host']) && !empty($_POST['username']) && isset($_POST['password'])) {
			
			## CMD style ^^
			$style = "<link href='//fonts.googleapis.com/css?family=VT323:400' rel='stylesheet' type='text/css'>";
			$style .= "<style>body{font-family: VT323;color:#f2f2f2}th,td{border: 1px dashed #f2f2f2;padding:5px}</style>";
			echo $style;
            
            $mysqlhost = $_POST['host'];            
            $mysqlusr = $_POST['username'];            
            $mysqlpass = $_POST['password'];
            
			## Koneksi ke mysql
            mysql_connect($mysqlhost, $mysqlusr, $mysqlpass);            
            
            if (!empty($_POST['query'])) {
                
                if (get_magic_quotes_gpc())
                    $_POST['query'] = stripslashes($_POST['query']);
                
				## Menentukan database yang akan di query
                mysql_select_db($_POST['dbname']);
                
				## Menjalankan query
                $result = mysql_query($_POST['query']);
                
                if ($result) {
					
					## Menampilkan hasil query table
                    if (@mysql_num_rows($result)) {
                        
?>

            <table>  
                <thead>  
                    <tr>  
                    
<?php
                        
						## Menampilkan semua field di dalam table
                        for ($i = 0; $i < mysql_num_fields($result); $i++) {                            
                            echo ('<th>' . mysql_field_name($result, $i) . '</th>');                            
                        }
                        
?>

                    </tr>  
                </thead>                
                <tbody>  
                
<?php
                        
						## Menampilkan semua isi field di dalam table
                        while ($row = mysql_fetch_row($result)) {                            
                            echo ('<tr>');                            
                            for ($i = 0; $i < mysql_num_fields($result); $i++) {                                
                                echo ('<td>' . htmlentities($row[$i], ENT_QUOTES) . '</td>');                                
                            }                            
                            echo ('</tr>');                            
                        }
                        
?>  

                </tbody> 
            </table>
            
<?php
                     
					## Menampilkan pesan sukses query selain ke table. Misalnya create database
                    } else {                        
                        echo ('Query OK: ' . mysql_affected_rows() . ' rows affected.');                        
                    }                    
                } 
				
				## Menampilkan pesan error karena perintah sql yang salah
				else {                    
                    echo ('Query Failed: ' . mysql_error());                    
                }
                
            } else if (empty($_POST['dbname']) && empty($_POST['query'])) {
?>    

			<table>  
                <thead>  
                    <tr>                      
						<th>db_name</th>
                    </tr>  
                </thead>                
                <tbody>  

<?php
                
                $dbs = mysql_list_dbs();   
					
				## Menampilkan semua nama database yang ada di server
                for ($i = 0; $i < mysql_num_rows($dbs); $i++) {                    
                    $dbname = mysql_db_name($dbs, $i);                    
                    echo ('<tr><td>' . $dbname . '</td></tr>');                    
                }
                
?>

				</tbody> 
			</table>

<?php
            }
            
        }
    }
}

## Function untuk menecek url CDN apakah CSS atau JS ##
function parseurl($url) {
    return preg_replace("#(.+)?\.(\w+)(\?.+)?#", "$2", $url);
}

?> 
