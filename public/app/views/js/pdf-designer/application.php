<?php
/** application PDF designer
 * -----------------------------------------------------------------------------
 */
?>
<script>
function designer(data, defines) {
    // handle date
    const d = new Date( data.date );
    const options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    };
    var elDate = d.toLocaleDateString('el-GR', options);
    var year = d.getFullYear().toString();

    // var tick_parts = defines.ticket.match(/.{1,4}/g) ?? [];
    // var easyread_ticket = tick_parts.join('-');



    // get labels from id-specified objects
    let petition = 'Αίτηση';

    return {
        info: {
            title: data.subject,
            author: defines.organization,
            subject: data.subject,
            keywords: [petition, year].join(', '),
        },
        content: [
            {
                text: 'ΑΙΤΗΣΗ\n\n',
                style: 'header'
            },
            {
                alignment: 'justify',
                columns: [
                    {
                        width: 210,
                        text: ''
                    },
                    {
                        width: 'auto',
                        lineHeight: 1.35,
                        text:[
                            'ΠΡΟΣ\n',
                            {
                                style: 'bigger',
                                text: defines.organization
                            }
                        ]
                    }
                ]
            },
            {
                columns: [
                    {
                        width: 210,
                        lineHeight: 1.25,
                        alignment: 'left',
                        text: [
                            'Επώνυμο: ', { style: 'bigger', text: data.last_name },
                            '\n\nΌνομα: ', { style: 'bigger', text: data.first_name },
                            '\n\nΌνομα πατρός: ',{ style: 'bigger', text: data.father_name },
                            '\n\nAM: ',{ style: 'bigger', text: data.registration_number },
                            '\n\nΚλάδος/Ειδικότητα: ',{ style: 'bigger', text: data.sector },
                            '\n\nΣχολείο Οργανικής: ',{
                                style: 'bigger', text: data.belonging_school
                            },
                            // '\n\nΣχολείο Υπηρέτησης:\n\t',{
                            //     style: 'bigger', text: data.constants.organization
                            // },
                            '\n\nΤηλέφωνο Επικοινωνίας: ',{ style: 'bigger', text: data.phone },
                            '\n\nEmail: ', { style: 'bigger', text: data.email },
                            '\n\nΘέση Υπηρέτησης: ',{ style: 'bigger', text: data.position }
                            
                        ]
                    },
                    {
                        width: 'auto',
                        lineHeight: 1.33,
                        text: [
                            '\n\n',
                            {
                                style: 'bigger',
                                text: data.request+'\n',
                                alignment: 'left'
                            },
                            '\n\n',
                            {
                                text: elDate,
                                style: 'bigger',
                                alignment: 'center'
                            },
                            {
                                text: '\n\n'+ defines.applier +'\n',
                                style: 'bigger',
                                alignment: 'center'
                            },
                            {
                                style: 'bigger',
                                text: data.last_name +' '+data.first_name,
                                alignment: 'center'
                            }
                        ]
                    }
                ]
            }
        ],
        footer: {
          text: [
            'Αριθμός πρωτοκόλου: A124-2501-4568-7 / 2023-12-15',
            '\ndocument signature: ' + defines.ticket

          ],
          style: 'signature'
        },
        styles: {
            header: {
                fontSize: 20,
                bold: true,
                alignment: 'center',
                color: '#000'
            },
            bigger: {
                fontSize: 10,
                italics: false,
                bold: true,
                color: '#000'
                // decoration: 'underline'
            },
            signature: {
                fontSize: 8,
                italics: false,
                alignment: 'center',
                font: 'FiraMono'

            }
        },
        defaultStyle: {
            columnGap: 35,
            fontSize: 9,
            font: 'FiraSans'
        }
    };   
}

</script>