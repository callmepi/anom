<?php
/** penalty PDF designer
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
                text: [
                    'ΠΡΑΚΤΙΚΟ ',
                    'something',
                    ' / ',
                    'something else\n\n'
                ],
                style: 'header'
            },
            {
                alignment: 'left',
                text: [
                    {
                        style: 'bigger',
                        text: 'ΘΕΜΑ: '
                    },
                    'Πειθαρχική υπόθεση '+ data.of_article,
                    ' '+ data.student_names +', ',
                    'του τμήματος ' + data.of_department + '\n',

                    {
                        style: 'bigger',
                        text: 'ΗΜΕΡΑ: '
                    },
                    elDate,
                    ', Ωρα'+ data.time + '\n',

                    {
                        style: 'bigger',
                        text: 'ΤΟΠΟΣ: '
                    },
                    data.place + '\n',

                    {
                        style: 'bigger',
                        text: 'ΠΕΙΘΑΡΧΙΚΟ ΟΡΓΑΝΟ: \n'
                    }
                ]
            },

            {               
                columns: [
                    {
                        width: 6,
                        text: ''
                    },
                    {
                        width: 'auto',
                        text:[
                            {
                                style: 'bigger',
                                text: 'Πειθαρχικό Συμβούλιο από τους:\n'
                            },
                            {
                                style: 'bigger',
                                text: 'Πρόεδρος: '
                            },
                            data.president + '\n',

                            {
                                style: 'bigger',
                                text: 'Μέλη: '
                            },
                            data.members.join(', ') + '\n'
                        ]
                    }
                ]
            },
            {
                alignment: 'left',
                text: [
                    {
                        style: 'bigger',
                        text: 'Εισηγητής: '
                    },
                    data.rapporteur + '\n',

                    {
                        style: 'bigger',
                        text: 'Κατηγορία: '
                    },
                    data.charge + '\n\n',

                    {
                        style: 'bigger',
                        text: 'Απολογία '+ data.of_article +': '
                    },
                    data.apology + '\n\n',

                    data.decision_reasoning + '\n\n'
                ]
            },
            {
                alignment: 'center',
                style: 'bigger',
                text: 'Το Πειθαρχικό Συμβούλιο αποφασίζει'
            },
            {
                alignment: 'left',
                text: data.decision
            },

            {
                columns: [
                    {
                        width: 210,
                        alignment: 'center',
                        text: [
                            { style: 'bigger', text: 'Πρόεδρος:\n' },
                            data.president
                        ]
                    },
                    {
                        width: 'auto',
                        alignment: 'center',
                        text: [
                            { style: 'bigger', text: 'Μέλη:\n' },
                            data.members.join('\n')
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
                fontSize: 12,
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
            font: 'FiraSans',
            lineHeight: 1.35
        }
    };   
}

</script>