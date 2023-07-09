<script>

    // supplementary functions to select2 control
    ////////////////////////////////////////////////////////////////////////////

    /** getValues
     * --- -- -- - - -
     * Get selected value(s) of Select2 control
     * 
     * @param (selector) arg: source-element
     * @return (array) value(s)
     */
    function getValues(srcElm) {
        var res = [];
        var obj = srcElm.select2('data');   // get delected data array

        if (!obj.length === 0); {
            var i = 0;
            obj.forEach(function(elm) { 
                res.push(elm.id)
            });
        }
        return res;
    }

    /** select2_set_text 
     * --- -- -- - - -
     * select an (select2-)option by text
     * onto a (single) select2 control
     * 
     * @param control = jquery selector,
     * @param t (string) = text of option
     */
    function select2_set_text(control, t) {
        opts = control.select2()[0].options;
        var id;
        for(i=0; i<opts.length; i++) { if (opts[i].text == t) id = i; }
        control.val(id).trigger('change');
    }

    /** get_id_of_text
     * --- -- -- - - -
     * get id (=value) of an option with `t` as text
     * onto a select2 control;
     * 
     * Can be used on single or multiple select2 contols
     * 
     * @param control = jquery selector,
     * @param t (string) = text of option
     */
    function get_id_of_text( control , t) {
        opts = control.select2()[0].options;
        var id = false;
        for(i=0; i<opts.length; i++) { if (opts[i].text == t) id = i; }
        return id;
    }

    /** select2_set_multi_text
     * --- -- -- - - -
     * select several (select2-)options by text
     * 
     * @param control  = jquery selector,
     * @param t_list (string) = list of selected texts
     */
    function select2_set_multi_text(control, t_list) {   /* define function */
        var ids = [];
        t_list.forEach (t => { let id = get_id_of_text(control, t);
            if (id !== false) ids.push(id);
        })
        control.val(ids).change();
    }

    /** selected_text
     * --- -- -- - - -
     * get selected text of a select2 control
     */
    function selected_text(control) {
        if (typeof control.select2('data') === 'undefined') return false;
        else if (control.select2('data').length == 0) return false;
        else return control.select2('data')[0].text;
    }

    /**
     * get all selected texts from a multi-select2 contol
     */
    function selected_multitexts(control) {
        if (typeof control.select2('data') === 'undefined') return false;
        else if (control.select2('data').length == 0) return false;
        else {
            var result = [];
            control.select2('data').forEach( item => { result.push(item.text); });
            return result;
        }
    }

</script>