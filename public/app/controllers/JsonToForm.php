<?php
namespace app\controllers;

class JsonToForm
{
    /**
     * ## Help Constants and Functions -----------------------------------------
     * /////////////////////////////////////////////////////////////////////////
     *
     * 
     * Cache fields options (json format) //////////////////////////////////////
     * -------------------------------------------------------------------------
     *   $formJSON = file_get_contents('config/kallassa.json');
     *   define("FORMJSON", $formJSON);
     *
     * Cache CCODE attributes (json format) ////////////////////////////////////
     * -------------------------------------------------------------------------
     *   $codeJSON = file_get_contents('config/ccode.json');
     *   define("CODEJSON", $codeJSON);
     *
     * Parse anythig from a field //////////////////////////////////////////////
     * ARGS:
     *    $fkey   = keyname (as in json fields oprions)
     *    $inp    = input value (string)
     * RETURN: array(
     *    'label' = field label,
     *    'multi' = (true|false) is multiple choice (select|radio|check) or not,
     *    'value' = human readable value (or array of values if multi)
     * ) -----------------------------------------------------------------------
     */
    public static function parse_any($fkey, $inp ="")
    {
         $formARRAY = json_decode(FORMJSON);

         foreach ($formARRAY as $key => $section) {
            foreach ($section->childs as $kk => $field) {

                // find the field
                if ($field->nam == $fkey) {

                    $values = array();    // human readble values if select (comma separated)

                    if ($field->typ == 'select') {
                    $multi = true;

                    if ($inp !='') {
                        $codes = explode(",", $inp);    // split coded values
                        foreach ($field->opt as $kkk => $v) {
                            if (in_array($v->id, $codes)) {   // check if option is in array
                                $values[] = ($field->nam == 'ccode') ? ($v->id.": ".$v->tag) : $v->tag;
                            }
                        }
                    }

                    }
                    else $multi = false;

                    // return everything in an array
                    return  array(
                            'label' => $field->lab,
                            'multi' => $multi,
                            'value' => ($multi ? $values : $inp)
                            );
                }
            }
         }

        // if not escaped already, then name return same
        return    array('label' => $fkey, 'multi' => false, 'value' => $inp );
    }



    // Parse anythig from ccode
    public static function parse_ccode($ckey)
    {
        $ccARRAY = json_decode(CODEJSON);

        foreach ($ccARRAY as $key => $val) {
            // find the field
            if ($val->id == $ckey) {
                // return everything in an array
                return  $val;
            }
        }
    }



    // Preview Local Datetime //////////////////////////////////////////////////
    // (in Greek format and names )
    // -------------------------------------------------------------------------
    public static function local_dt($t)
    {
        $day  = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
        $dayL = array("Κυρ", "Δευ", "Τρι", "Τετ", "Πεμ", "Παρ", "Σαβ");
        $mon  = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        $monL = array("Ιαν", "Φεβ", "Μαρ", "Απρ", "Μαϊ", "Ιουν", "Ιουλ", "Αυγ", "Σεπ", "Οκτ", "Νοε", "Δεκ");
        $tm   = array("am", "pm");
        $tmL  = array("πμ", "μμ");
        $r = date("D. j M. Y, h:i:sa", $t);
        $r = str_replace($day, $dayL, $r);
        $r = str_replace($mon, $monL, $r);
        $r = str_replace($tm, $tmL, $r);
        return $r;
    }


    // Part of string
    // UTF-8 SAFE
    public static function str_part($str, $len)
    {
        return (mb_strlen($str) > $len) ? mb_substr($str, 0, $len-1) ."…" : $str;
    }


    /** json_form
     * -------------------------------------------------------------------------
     * construct a form (html, javasctipt)
     * from a json designer
     * 
     * @param stdClass $formJson : form descriptor
     * 
     * @param array $require_identity : array of properties and values;
     *    they identify the record; injected into form as hidden fields
     * 
     * @return array [ html=>(string), init_js=>(string), values_js=>String ]
     * -------------------------------------------------------------------------
     */
    public static function json_form($formJson, $require_identity = [])
    {
        // $formJson = json_decode(json_encode($form));

        $default_outer  = $formJson->defaults->outer_class ?? '';
        $default_inner  = $formJson->defaults->inner_class ?? '';
        $default_type   = $formJson->defaults->type ?? 'text';
        $default_values = $formJson->defaults ?? ((object) ['nothing' => true]);

        // TODO handle $form->defaults->source;

        // Script Workflow:
        // ---------------------------------------------------------------------
        // 1. read fields options
        // 2. generate HTML
        // 3. generate JS needed

        // the UI
        // ---------------------------------------------------------------------
        // Fields are grouped in sections
        // These sections presented in tabs (or accordion if mobile device)
        // Two (2) sections incduded to inform the user of empty fields or errors

        // Libraries and FrameWorks used
        // ---------------------------------------------------------------------
        // Bootstrap 4.3 is used for easy responsive HTML code
        // JQuery 3.3 is used to handle user interation
        // Select2 is used to make select-boxes (single or multiple) easier to use

        // read fields options (json format) ///////////////////////////////////
        // ---------------------------------------------------------------------

        // Init strigns for JS generator (needed to handle everything) /////////
        // ---------------------------------------------------------------------
        $checkFilledJS = "";    // JS for checking empty fields
        $ref2Select2JS = "";    // references to Select2-controls Javascript
        $initSelectsJS = "";    // js to initialize select fields (single or multiple)


        $identity = "";     // construct required identity hidden fields
        foreach ($require_identity as $group) {
            $identity .= "
                <input type='hidden' name='". $group['name']
                    ."' value='". $group['value'] ."'>";
        }

        // now constuct the form body
        $html = "{$identity}
            <div class='row'>
            ";

        foreach ($formJson->form as $key => $field) {

            $attributes = $field->attributes ?? [];         // attributes array
            $type = $field->type ?? $default_type;
            $class = $field->class ?? "";
            $label = $field->label;
            $name = $field->name ?? $field->label;

            // handle value assignment
            if (isset($field->value)) {         // if value property is set
                $set_value = true;              // (flag: value seted)
                $value = $field->value;
                // $value = (($field->value == 'auto') && isset($default_values->$name))
                //     ? $default_values->$name    // set auto value
                //     : $field->value;            // or other set-value
            
            } else if (in_array('auto', $attributes) && isset($default_values->$name)) {
                $set_value = true;                  // (flag: value seted)
                $value = $default_values->$name;    // set auto value
                // echo "\n{$name}: $value";

            } else {
                $set_value = false;
            }
            // print_r([ $set_value, $name, ($default_values->$name ?? 'none') ]);
                        
            $appendKey = in_array('append-key', $attributes) ? true : false;    // append key (for selects)
            $required = in_array('required', $attributes) ? 'required' : '';    // required
            $asterisk = in_array('required', $attributes) ? '*' : '';       // asterisk in label if required

            $html .= "
            <div class='form-group {$class}'>
                <label for='{$name}'>{$label} {$asterisk}</label>
                ";
            
            switch ($type) {

                case 'select':                         

                    if (in_array('multiple', $attributes)) {      // if select is multiple

                        $html .="
                            <select id='{$name}' class='form-select form-select-sm' name='{$name}[]' multiple='multiple' style='width:100%'>";

                        // prepare initialization of select2 (multiple)
                        $initSelectsJS .= "
                            var {$name} = $('#{$name}');
                            {$name}.select2({ width: '100%' });";

                        if ($set_value) {       // if default value is set
                            $initSelectsJS .= "
                            select2_set_multi_text( {$name} , {$value} );";

                        } else {
                            $initSelectsJS .= "
                                {$name}.val(null).trigger('change');";
                        }

                        // prepare check if empty field
                        $checkFilledJS .= "
                            if (getValues({$name}) =='') { empty += '{$label} {$asterisk}<br />'; }
                            values.{$name} = getValues($name);";


                    } else {    // select is single; draw select + add empty option

                        $html .="
                            <select id='{$name}' class='form-select form-select-sm' name='{$name}' style='width:100%'>";

                        // prepare initialization of select2 (single)
                        $initSelectsJS .= "
                            var {$name} = $('#{$name}');
                            {$name}.select2();";

                        if ($set_value) {       // if default value is set
                            $initSelectsJS .= "
                                select2_set_text( {$name} , '{$value}' );";

                        } else {
                            $initSelectsJS .= "
                                {$name}.val(null).trigger('change');";
                        }

                        // prepare check if empty field
                        $checkFilledJS .= "
                            if (getValues({$name}) =='') { empty += '{$label} {$asterisk}<br />'; }
                            values.{$name} = getValues($name);";
                        
                    }

                    // inject options
                    if (isset($field->options)) {
                    foreach ($field->options as $key => $val) {
                        $html .= "
                            <option value='{$key}'>{$val}</option>";
                    }}

                    // close select
                    $html .= "</select>";

                    break;


                case 'text':

                    $def_value = ($set_value) ? "value='{$value}'" : "value=''";

                    $html .= "
                        <input type='text' class='form-control form-control-sm' name='{$name}' $def_value {$required}>";

                    $checkFilledJS = "
                        if ($('input[name={$name}]').val() =='') { empty += '{$label} {$asterisk}<br />'; }
                        values.{$name} = $('input[name={$name}]').val();";

                    break;


                case 'password':

                    $def_value = ($set_value) ? "value='{$value}'" : "value=''";

                    $html .= "
                        <input type='password' class='form-control form-control-sm' name='{$name}' {$required}>";

                    $checkFilledJS = "
                        if ($('input[name={$name}]').val() =='') { empty += '{$label} {$asterisk}<br />'; }
                        values.{$name} = $('input[name={$name}]').val();";

                    break;

                            
                case 'integer':

                    $def_value = ($set_value) ? "value='{$value}'" : "value=''";

                    $html .= "
                        <input type='number' class='form-control form-control-sm' name='{$name}' min='0' step='1' $def_value {$required}>";

                    $checkFilledJS .= "
                        if ($('input[name={$name}]').val() =='') { empty += '{$label} {$asterisk}<br />'; }
                        values.{$name} = $('input[name={$name}]').val();";

                    break;


                case 'textarea':

                    $def_value = ($set_value) ? "{$value}" : "";

                    $html .= "
                        <textarea class='form-control form-control-sm' name='{$name}' {$required}>{$def_value}</textarea>";

                    $checkFilledJS .= "
                        if ($('textarea[name={$name}]').val() =='') { empty += '{$label} {$asterisk}<br />'; }
                        values.{$name} = $('textarea[name={$name}]').val();";

                    break;
                    

                case 'date':

                    $html .= "
                        <input type='date' class='form-control form-control-sm' name='{$name}' {$required}>";

                    $checkFilledJS .= "
                        if ($('input[name={$name}]').val() =='') { empty += '{$label} {$asterisk}<br />'; }
                        values.{$name} = $('input[name={$name}]').val();";

                    break;


                case 'email':

                    $def_value = ($set_value) ? "value='{$value}'" : "value=''";

                    $html .= "
                        <input type='email' class='form-control form-control-sm' name='{$name}' {$def_value} {$required}>";

                    $checkFilledJS .= "
                        if ($('input[name={$name}]').val() =='') { empty += '{$label} {$asterisk}<br />'; }
                        values.{$name} = $('input[name={$name}]').val();";

                    break;


                default:    // label, acts as common text

                    //$html .= "<label>{$label}</label";

            }

            $html .= '</div>';
        }

        return [
            'html' => $html .'</div>',
            'init_js' => $initSelectsJS,
            'values_js' => $checkFilledJS
        ];

    }

}




/***
 * Script Workflow:
 * -----------------------------------------------------------------------------
 * 1. read fields options
 * 2. generate HTML
 * 3. generate JS needed

 * the UI
 * -----------------------------------------------------------------------------
 * Fields are grouped in sections
 * These sections presented in tabs (or accordion if mobile device)
 * Two (2) sections incduded to inform the user of empty fields or errors

 * Libraries and FrameWorks used
 * -----------------------------------------------------------------------------
 * Bootstrap 4.3 is used for easy responsive HTML code
 * JQuery 3.3 is used to handle user interation
 * Select2 is used to make select-boxes (single or multiple) easier to use


 * read fields options (json format) ///////////////////////////////////////////
 * -----------------------------------------------------------------------------
    $formJSON = file_get_contents('config/kallassa.json');
    $formARRAY = json_decode($formJSON);


 * Init strigns for JS generator (needed to handle everything) /////////////////
 * -----------------------------------------------------------------------------
    $checkFilledJS = "";    // JS for checking empty fields
    $ref2Select2JS = "";    // references to Select2-controls Javascript
    $initSelectsJS = "";    // js to initialize select fields (single or multiple)
*/
