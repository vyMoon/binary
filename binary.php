function search($needle, $dir) {
    $splitter = '\x0A';
    $splitter2 = '\t';
    $step = 1000000;

    $remain = '';

    $handle = fopen($dir, 'r');

    while (!feof($handle)) {
        $content = fread($handle, $step);
        $lastIndex = strrpos($content, $splitter);
        $lastPart = substr($content, $lastIndex - $step);
        $content = substr($content, 0, $lastIndex + 4 - $step);
        $firstIndex = strpos($content, $splitter);
        $firstPart = substr($content, 0, $firstIndex + 4);
        $content = substr($content, $firstIndex + 4);

        $remain .= $firstPart . $lastPart;

        $content = explode($splitter, $content);
        array_pop($content);
        for($i = 0; $i < count($content); $i++) {
            $content[$i] = explode($splitter2, $content[$i]);
        }

        $less = 0;
        $more = count($content) - 1;

        if (strnatcmp($content[$more][0], $needle) > 0) {

            while($less <= $more) {

                $middle = $less + floor(($more - $less) / 2);
        
                $compare = strnatcmp($content[$middle][0], $needle);
        
                if (strnatcmp($content[$less][0], $needle) === 0) {
                    return $content[$less][1];
                }
                if (strnatcmp($content[$more][0], $needle) === 0) {
                    return $content[$more][1];
                }
        
                if ($compare > 0) {
                    $more = $middle - 1;
                } elseif ($compare < 0) {
                    $less = $middle + 1;
                } elseif ($compare == 0) {
                    return $content[$middle][1];
                }
                
            }

        }

    }

    $remain = substr($remain, 4);
    $content = $remain;
    
    $content = explode($splitter, $content);
    array_pop($content);
    for($i = 0; $i < count($content); $i++) {
        $content[$i] = explode($splitter2, $content[$i]);
    }

    $less = 0;
    $more = count($content) - 1;
    
    while($less <= $more) {

        $middle = $less + floor(($more - $less) / 2);

        $compare = strnatcmp($content[$middle][0], $needle);

        if (strnatcmp($content[$less][0], $needle) === 0) {
            return $content[$less][1];
        }
        if (strnatcmp($content[$more][0], $needle) === 0) {
            return $content[$more][1];
        }

        if ($compare > 0) {
            $more = $middle - 1;
        } elseif ($compare < 0) {
            $less = $middle + 1;
        } elseif ($compare == 0) {

            return $content[$middle][1];
        }
        
    }

    return 'undef';

}
