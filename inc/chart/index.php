<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RapidCode.IR</title>
    <style>
    body {
        text-align: center;
        overflow-x: hidden;
    }

    #introduce {
        color: white;
        text-decoration: none;
        font-weight: bold;
        display: block;
        width: 100%;
        padding: 5px 10px;
        background-color: #4CAF50;
        text-align: center;
        font-size: 25px;
        margin-bottom: 45px;
    }

    #wrapper-chart{
        width: 600px;
        height: 400px;
        display: inline-block;
    }
    </style>
</head>

<body>
    <a id="introduce" target="_blank" href="https://rapidcode.ir">رپید کد - کتابخانه مجازی برنامه نویسان</a>

    <?php 

    $data = [];
    $limit = 6;

    for($i=0;$i<$limit;$i++){
        $current_list = [];

        $numberLoop = $i + 1;

        $current_list['label'] = "chart {$numberLoop}";
        $current_list['value'] = rand(20 , 361);

        $color = rand(0 , 255) . "," . rand(0 , 255) . "," . rand(0 , 255);
        $color = "rgba(" . $color . ", X)";

        $bg_color = str_replace("X", "0.2", $color);
        $border_color = str_replace("X", "1", $color);

        $current_list['color'] = [$bg_color,$border_color];

        $data[$i] = $current_list;
    }

    $json_data = json_encode($data);

    ?>

    
    <div id="wrapper-chart">
    <canvas id="chrt"></canvas>
    </div>


<script src="lib/chart.min.js"></script>
<script>
    <?php echo "const json_args = '{$json_data}';" ?>
    const chartArgs = JSON.parse(json_args);
</script>
<script>
    
    if(typeof chartArgs != "undefined" && chartArgs[0]){
    var ctx = document.getElementById('chrt');

    cleanChartArgs = {
        labels : [] , 
        value : [] ,
        bgColor : [] ,
        borderColor : []
    };


    for(let i=0;i<chartArgs.length;i++){
        const currentIndex = chartArgs[i];

        cleanChartArgs.labels.push(currentIndex.label);
        cleanChartArgs.value.push(currentIndex.value);
        cleanChartArgs.bgColor.push(currentIndex.color[0]);
        cleanChartArgs.borderColor.push(currentIndex.color[1]);

    }

    if(ctx){

    var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: cleanChartArgs.labels,
        datasets: [{
            label: ['full data'],
            data: cleanChartArgs.value,
            backgroundColor: cleanChartArgs.bgColor,
            borderColor: cleanChartArgs.borderColor,
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
});
}
    }

</script>
</body>
</html>