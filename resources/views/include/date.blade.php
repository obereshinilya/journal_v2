<input type='text' id="date_start" placeholder="Выберите дату..." class='datepicker-here header_blocks' style="" />
<script>
    // Про него можно почитать тут http://t1m0n.name/air-datepicker/docs/index-ru.html
    var today = new Date();
    if (today.getHours() < {{\App\Models\Setting::where('name_setting', '=', 'start_smena')->first()->value-1}}) {
        today.setDate(today.getDate() - 1)
    }
    new AirDatepicker('#date_start',
        {
            selectedDates: [today],
            autoClose: true,
            maxDate: today,
            startDate: today,
            keyboardNav: true,
            buttons: ['today', 'clear'],
            onSelect: function (date){
                try{
                    get_table_data()
                }catch (e){

                }
            }
    })


</script>
