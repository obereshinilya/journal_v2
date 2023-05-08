<input type='text' id="period" placeholder="Выберите период..." class='datepicker-here header_blocks' style="" />
<script>
    // Про него можно почитать тут http://t1m0n.name/air-datepicker/docs/index-ru.html
    var today = new Date();
    var last_week = new Date().setDate(today.getDate() - 7)
    new AirDatepicker('#period',
        {
            range: true,
            multipleDatesSeparator: ' - ',
            selectedDates: [last_week, today],
            autoClose: true,
            maxDate: today,
            startDate: last_week,
            endDate: today,
            keyboardNav: true,
            buttons: ['today', 'clear'],
            onSelect: function (date){
                if (date['date'].length === 2){
                    try{
                        render_graph()
                    }catch (e){
                        try{
                            get_table_data()
                        }catch (e){
                            console.log(e)
                        }
                    }
                }
            }
    })


</script>
