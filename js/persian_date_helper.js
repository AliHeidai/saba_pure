//function jal_to_greg(shamsi_date){
    JalaliDate = {
    g_days_in_month: [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
    j_days_in_month: [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29]
    };
    // var sep=shamsi_date.indexOf('/');
    // if(sep>-1){
    //     shamsi_date=shamsi_date.replaceAll('/','-');
       
    // }
    // let j_array=shamsi_date.split('-');
    
    // let j_y=j_array[0]
    // let j_m=j_array[1]
    // let j_d=j_array[2]
    JalaliDate.jalaliToGregorian = function(j_y, j_m, j_d) {
        j_y=1403;
        j_y = parseInt(j_y);
        j_m = parseInt(j_m);
        j_d = parseInt(j_d);
        var jy = j_y - 979;
        var jm = j_m - 1;
        var jd = j_d - 1;
        
        var j_day_no = 365 * jy + parseInt(jy / 33) * 8 + parseInt((jy % 33 + 3) / 4);
        for (var i = 0; i < jm; ++i) j_day_no += JalaliDate.j_days_in_month[i];

        j_day_no += jd;

        var g_day_no = j_day_no + 79;

        var gy = 1600 + 400 * parseInt(g_day_no / 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
        g_day_no = g_day_no % 146097;

        var leap = true;
        if (g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */
        {
            g_day_no--;
            gy += 100 * parseInt(g_day_no / 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
            g_day_no = g_day_no % 36524;

            if (g_day_no >= 365) g_day_no++;
            else leap = false;
        }

        gy += 4 * parseInt(g_day_no / 1461); /* 1461 = 365*4 + 4/4 */
        g_day_no %= 1461;

        if (g_day_no >= 366) {
            leap = false;

            g_day_no--;
            gy += parseInt(g_day_no / 365);
            g_day_no = g_day_no % 365;
        }

        for (var i = 0; g_day_no >= JalaliDate.g_days_in_month[i] + (i == 1 && leap); i++)
        g_day_no -= JalaliDate.g_days_in_month[i] + (i == 1 && leap);
        var gm = i + 1;
        var gd = g_day_no + 1;

        gm = gm < 10 ? "0" + gm : gm;
        gd = gd < 10 ? "0" + gd : gd;

        return [gy, gm, gd];
    }
//}


function jal_to_greg(shamsi_date){
    var sep=shamsi_date.indexOf('/');
    if(sep>-1){
        shamsi_date=shamsi_date.replaceAll('/','-');
       
    }
    let j_array=shamsi_date.split('-');
    
    let j_y=j_array[0]
    let j_m=j_array[1]
    let j_d=j_array[2]
    let sdate= JalaliDate.jalaliToGregorian (j_y, j_m, j_d) 
    console.log('sdate',sdate);
}