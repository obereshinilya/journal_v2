<input type='text' id="only_time" placeholder="Выберите время..." class='datepicker-here' style="text-align: center; margin: 0 10px; width: 70px" />
<script>
    // Про него можно почитать тут http://t1m0n.name/air-datepicker/docs/index-ru.html
    var today = new Date();
    today.setMinutes(0)
    new AirDatepicker('#only_time',
        {
            timepicker: true,
            onlyTimepicker: true,
            maxHours: 23,
            maxMinutes:0,
            onSelect: function (date){
                try{
                    save_setting('start_smena', date['formattedDate'])
                }catch (e){

                }
            }
    })
</script>
