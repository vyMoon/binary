function binary($content, $needle) {

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
        } elseif ($compare === 0) {
            return $content[$middle][1];
        }
    }

    return 'undef';

}

function search2($needle, $dir) {
    $splitter = '\x0A';
    $splitter2 = '\t';
    $step = 1000000;

    $remain = '';

    $handle = fopen($dir, 'r');

    while (!feof($handle)) {
        $content = fread($handle, $step);
        
        $firstIndex = strpos($content, $splitter);
        $firstPart = substr($content, 0, $firstIndex + 4);
        $content = substr($content, $firstIndex + 4);

        $contentLen = strlen($content);

        $lastIndex = strrpos($content, $splitter);
        if ( ($lastIndex + 4 - $contentLen) !== 0 ) {
            $lastPart = substr($content, $lastIndex + 4 - $contentLen);
        }
        if ( ($lastIndex + 4 - $contentLen) !== 0 ) {
            $content = substr($content, 0, $lastIndex + 4 - $contentLen);
        }

        $remain .= $firstPart . $lastPart;
        $content = substr($content, 0, -4);

        $contentLen = strlen($content);
        $lastIndex = strrpos($content, $splitter);
        $lastPart = substr($content, $lastIndex + 4 - $contentLen);
        $lastPart = explode($splitter2, $lastPart);

        if (strnatcmp($lastPart[0], $needle) > 0) {
            $content = explode($splitter, $content);

            for($i = 0; $i < count($content); $i++) {
                $content[$i] = explode($splitter2, $content[$i]);
            }

            $answer = binary($content, $needle);

            if ($answer !== 'undef') {
                return $answer;
            }
        }
    }

    $remain = explode($splitter, $remain);
    array_pop($remain);
    for($i = 0; $i < count($remain); $i++) {
        $remain[$i] = explode($splitter2, $remain[$i]);
    }

    return binary($remain, $needle);

}
