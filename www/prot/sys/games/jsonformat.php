<?php
function json_format($json) {
    $tab = "  ";
    $new_json = "";
    $indent_level = 0;
    $in_string = false;
    $in_int_array = false;

    $json_obj = json_decode($json);

    if($json_obj === false)
        return false;

    $json = json_encode($json_obj);
    $len = strlen($json);

    for($c = 0; $c < $len; $c++) {
        $char = $json[$c];
        switch($char) {
            case '{':
            case '[':
                if(!$in_string) {
                	if($json[$c+1]>='0' && $json[$c+1]<='9' || $json[$c+1]=='-') {
                		$c++;
                		$new_json .= '[';
	               		while($json[$c]!=']') {
                			$new_json .= $json[$c];
                			if($json[$c]==',')
                				$new_json .= ' ';
                			$c++;
                		}
                		$new_json .= ']';
                	} else if($json[$c+1]==']') {
                		$c++;
                		$new_json .= "[]";
	                } else {
                		$new_json .= $char . "\n" . str_repeat($tab, $indent_level+1);
	                    $indent_level++;
	                }
                } else {
                    $new_json .= $char;
                }
                break;
            case '}':
            case ']':
                if(!$in_string) {
                    $indent_level--;
                    $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
                } else {
                    $new_json .= $char;
                }
                break;
            case ',':
                if(!$in_string) {
                    $new_json .= ",\n" . str_repeat($tab, $indent_level);
                } else {
                    $new_json .= $char;
                }
                break;
            case ':':
                if(!$in_string) {
                    $new_json .= ": ";
                } else {
                    $new_json .= $char;
                }
                break;
            case '"':
                if($c == 0 || $json[$c-1] != '\\') {
                    $in_string = !$in_string;
                }
            default:
                $new_json .= $char;
                break;                   
        }
    }

    return $new_json;
}

?>
