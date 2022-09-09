<?php
array_shift($argv);


function parsing($argv) {
    $file = $argv[0];

    if(!file_exists($file)) {
        echo "le fichier que vous utilisez n'existe pas.";
        return;
    }
    $handel = fopen($file , 'r');
    $contents = fread($handel, filesize($file));  
    // preg_match('/<title>(.*?)<\/title>/s' , $contents , $match);
    preg_match('/<meta property=og:title content="(.*?)">/s' , $contents , $title);
    preg_match('/<span class="glyphicons glyphicons-calendar x1"><\/span>(.*?)<\/span>/s' , $contents , $releas_date);
    preg_match('/<div class=overview dir=auto>(.*?)<\/p>/s' , $contents , $summary);
    $summary[1] = str_replace('<p>' , '' , $summary[1]);
    preg_match('/<p><strong><bdi>Status<\/bdi><\/strong>(.*?)<\/p>/s' , $contents , $status);
    preg_match('/<p><strong><bdi>Runtime<\/bdi><\/strong>(.*?)<\/p>/s' , $contents , $duration);
    preg_match('/<p><strong><bdi>Budget<\/bdi><\/strong>(.*?)<\/p>/s' , $contents , $budget);
    if(!isset($budget[1])) {
        $budget[1] = '?';
    }
    preg_match('/<p><strong><bdi>Revenue<\/bdi><\/strong>(.*?)<\/p>/s' , $contents , $revenue);
    if(!isset($revenue[1])) {
        $revenue[1] = '?';
    }
    preg_match('/<p><strong><bdi>Original Language<\/bdi><\/strong>(.*?)<\/p>/s' , $contents , $originalLanguage);
    preg_match('/<h4><bdi>Genres<\/bdi><\/h4>(.*?)<\/ul>/s' , $contents , $genre);
    $genre = str_replace('<li>' , '' , $genre[1]);
    $genre = str_replace('<ul>' , '' , $genre);
    $genre = explode('</a></li>' , $genre);
    $arr_genre = [];
    foreach($genre as $value) {
        preg_match('/<a(.*?)>/s' , $value , $str );
        $value = str_replace($str , '' , $value);
        $value = preg_replace("/\s+/", "", $value);
        array_push($arr_genre , $value);
    }
    array_pop($arr_genre);
   

    preg_match('/<h4><bdi>Keywords<\/bdi><\/h4>(.*?)<\/ul>/s' , $contents , $keywords);
    $keywords = str_replace('<li>' , '' , $keywords[1]);
    $keywords = str_replace('<ul>' , '' , $keywords);
    $keywords = explode('</a></li>' , $keywords);
    $arr_keywords = [];
    foreach($keywords as $value) {
        preg_match('/<a(.*?)>/s' , $value , $str );
        $value = str_replace($str , '' , $value);
        
        $value = preg_replace("/\s+/", "", $value);
       
        array_push($arr_keywords , $value);
    }
    array_pop($arr_keywords);
    


    $arr = ['status' => 'ok',
            'result' => ['movie' => ['title' => $title[1],
                                     'releasDate' => $releas_date[1],
                                     'summary' => $summary[1],
                                     'status' => $status[1], 
                                     'duration' => $duration[1],
                                     'budget' => $budget[1],
                                     'revenue' => $revenue[1],
                                     'OriginalLanguage' => $originalLanguage[1],
                                     'genre' => $arr_genre,
                                     'keywords' => $arr_keywords,
                                     ]            
                        ]
            ];
    $json =  json_encode($arr, JSON_FORCE_OBJECT);      
    $json = file_put_contents("result.json", $json);
}
parsing($argv);