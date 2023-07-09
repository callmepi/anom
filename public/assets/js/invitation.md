# SELECT2 HANDLING

handling select2 properties (case-study javascript code)


## get all options' loop through all ...

    opts = $('#sector').select2()[0].options
    for(i=0; i<opts.length; i++) { el = opts[i]; console.log(el.value, el.text); }



## set option by selected 'TEXT'

    var id;
    for(i=0; i<opts.length; i++) { if (opts[i].text == 'TEXT') id = i; }
    $('#sector').val(id).trigger('change);



## prepare as functions

    function select2_set_text(control, t) {   /* define function */
        opts = control.select2()[0].options;
        var id;
        for(i=0; i<opts.length; i++) { if (opts[i].text == t) id = i; }
        control.val(id).trigger('change');
    }
    select2_set_text( $('#sector') , '04 Physics')   /* call */



## in multiple select2 ...

    function get_id_of_text( control , t) {    /* define id from text */
        opts = control.select2()[0].options;
        var id = false;
        for(i=0; i<opts.length; i++) {
            if (opts[i].text == t) id = i;
        }
        return id;
    }
    function select2_set_multi_text(control, t_list) {   /* define function */
        var ids = [];
        t_list.forEach (t => { let id = get_id_of_text(control, t);
                if (id !== false) ids.push(id);
        })
        control.val(ids).change();
    }
    select2_set_multi_text( $('#tags') , ['crime', '90s'])  /* call */

