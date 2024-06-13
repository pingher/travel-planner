<?php 
    $title = "Log In";
    function get_content(){
        require_once '../../controllers/connection.php';

        // find date & day
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $date = date("Y-m-d");
        $day = date("l");

        // filtering
        if(isset($_POST['filterweather'])){
            $filterchoice = $_POST['filterweather'];
        }else{
            $filterchoice = 'johor';
        }

        if ($filterchoice == 'johor') {            
            $location_state="Johor";
        }
        if ($filterchoice == 'kedah') {            
            $location_state="Kedah";
        }
        if ($filterchoice == 'kelantan') {
            $location_state="Kelantan";
        }
        if ($filterchoice == 'melaka') {            
            $location_state="Melaka";
        }
        if ($filterchoice == 'sembilan') {            
            $location_state="Negeri Sembilan";
        }
        if ($filterchoice == 'pahang') {           
            $location_state="Pahang";
        }
        if ($filterchoice == 'penang') {            
            $location_state="Penang";
        }
        if ($filterchoice == 'perak') {            
            $location_state="Perak";
        }
        if ($filterchoice == 'perlis') {            
            $location_state="Perlis";
        }
        if ($filterchoice == 'sabah') {
            $location_state="Sabah";
        }
        if ($filterchoice == 'sarawak') {
            $location_state="Sarawak";
        }
        if ($filterchoice == 'selangor') {
            $location_state="Selangor";
        }
        if ($filterchoice == 'terengganu') {
            $location_state="Terengganu";
        }

        $query_weather = "SELECT * FROM weather WHERE location_state='$location_state' AND date='$date';";
        $result_weather = mysqli_query($cn, $query_weather);
        $weathers = mysqli_fetch_assoc($result_weather);


        // FOR CHART
        // find the count of each weather type for today
        $query = "SELECT weather, COUNT(*) as count FROM weather WHERE date='$date' GROUP BY weather";
        $result_data = mysqli_query($cn, $query);

        // How many state has the same weather.
        $weather_counts = [];
        // total state
        $total_count = 0;

        $has_data = false;

        if ($result_data && mysqli_num_rows($result_data) > 0) {
            $has_data = true;
            while ($row = mysqli_fetch_assoc($result_data)) {
                $weather_counts[] = $row;
                $total_count += $row['count'];
            }
        }

        // Calculate the percentage of each weather type (CHART)
        $weather_percentages = array_map(function($weather) use ($total_count) {
            return [
                'name' => $weather['weather'],
                'y' => ($weather['count'] / $total_count) * 100
            ];
        }, $weather_counts);

        $weather_percentages_json = json_encode($weather_percentages);
        // END CHART

        // Query to get the average temperature in Malaysia
        $query_temp = "SELECT temperature FROM weather;";
        $result_temp = mysqli_query($cn, $query_temp);

        $total_temp = 0;
        $no_temps = 0;

        if ($result_temp && mysqli_num_rows($result_temp) > 0) {
            while ($row = mysqli_fetch_assoc($result_temp)) {
                $total_temp += $row['temperature'];
                $no_temps++;
            }
        }

        if($no_temps){
            $average_temp = $total_temp/$no_temps;
        }else{
            $average_temp = null;
        }

?>

<h1 class="pacifico-regular weathertitle m-5">Weather</h1>

<div class="mt-4">
    <div class="justify-content-center align-items center flex-row w-100 row gap-2">
        <!--Weather Display-->
        <div class="col-md-6 bg-warning gap-3 border rounded-5 py-4 d-flex justify-content-center align-items-center flex-column" style="width:400px;">
            <!-- filter choose state -->
            <form action="" method="POST">
                <select name="filterweather" id="weatherfilter" class="p-2 border rounded-4 open-sans" style="width:200px; font-size:20px;">
                    <option value="johor" <?php if($filterchoice == 'johor') echo 'selected'; ?>>Johor</option>
                    <option value="kedah" <?php if($filterchoice == 'kedah') echo 'selected'; ?>>Kedah</option>
                    <option value="kelantan" <?php if($filterchoice == 'kelantan') echo 'selected'; ?>>Kelantan</option>
                    <option value="melaka" <?php if($filterchoice == 'melaka') echo 'selected'; ?>>Melaka</option>
                    <option value="sembilan" <?php if($filterchoice == 'sembilan') echo 'selected'; ?>>Negeri Sembilan</option>
                    <option value="pahang" <?php if($filterchoice == 'pahang') echo 'selected'; ?>>Pahang</option>
                    <option value="penang" <?php if($filterchoice == 'penang') echo 'selected'; ?>>Penang</option>
                    <option value="perak" <?php if($filterchoice == 'perak') echo 'selected'; ?>>Perak</option>
                    <option value="perlis" <?php if($filterchoice == 'perlis') echo 'selected'; ?>>Perlis</option>
                    <option value="sabah" <?php if($filterchoice == 'sabah') echo 'selected'; ?>>Sabah</option>
                    <option value="sarawak" <?php if($filterchoice == 'sarawak') echo 'selected'; ?>>Sarawak</option>
                    <option value="selangor" <?php if($filterchoice == 'selangor') echo 'selected'; ?>>Selangor</option>
                    <option value="terengganu" <?php if($filterchoice == 'terengganu') echo 'selected'; ?>>Terengganu</option>
                </select>
            </form>
            <!-- Display weather for specific state selected -->
            <!-- weather is an image -->
            
            <!-- Display weather for specific state selected -->
            <?php if ($weathers): ?>
                <div>
                    <?php
                        $weather_image = ''; 
                        if($weathers['weather'] == 'Cloudy 🌥️'){
                            $weather_image = '/cloudy.png';
                        } elseif($weathers['weather'] == 'Sunny ☀️'){
                            $weather_image = '/sunny.png';
                        } elseif($weathers['weather'] == 'Rainy 🌧️'){
                            $weather_image = '/rainy.png';
                        }

                        if ($weather_image) {
                            echo "<img class='weatherimage' src='$weather_image' alt='Weather Image' />";
                        } else {
                            echo "No weather data available.";
                        }
                    ?>
                </div>
                <!-- Display Mostly ----(sunny, rainy or cloudy) -->
                <h4 class="open-sans w-100 text-center">Mostly <?php echo $weathers['weather']; ?></h4>
            <?php else: ?>
                <div>No weather data available.</div>
            <?php endif; ?>

        </div>



        <!--Temperature Average and Date-->
        <div class="col-md-6 d-flex flex-column gap-3" style="width:400px;">
            <!-- Temp in a day -->
            <div class="bg-warning w-100 border rounded-5 p-3 d-flex justify-content-center align-items-center flex-column" style="height:165px">
                <h4 class="open-sans">Temperature</h4>
                <?php if ($weathers): ?>
                    <h1 class="open-sans"><b><?php echo $weathers['temperature']; ?> °C</b></h1>
                <?php else: ?>
                    <h1>No data</h1>
                <?php endif; ?>
            </div>
            <!-- Day eg: wed and Date -->
            <div class="bg-warning w-100 border rounded-5 p-3 d-flex justify-content-center align-items-center flex-column" style="height:135px">
                    <h3 class="open-sans"><b><?php echo $day?></b></h3>
                    <h5 class="open-sans">━━ <?php echo $date?> ━━</h5>
            </div>
        </div>
    </div>

    <!-- Pie Chart Container -->
    <div id="container" class="p-3 rounded-5 mt-5 border-1 border-black border container d-flex justify-content-center align-items-center" style="width:60%; height:400px;"></div>
    <!-- average temp -->
    <div class="container d-flex justify-content-center align-items-center">
        <div class="bg-warning mb-5 border border-1 border-black rounded-1 d-flex align-items-center justify-content-center" style="height:50px; width:50%;">
            <?php if ($average_temp !== null): ?>
                <p class="open-sans m-0">Average Temperature : <b><?php echo round($average_temp, 2); ?></b> °C</p>
            <?php else: ?>
                <p class="m-0">No data</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<div class="container-fluid m-0 bg-warning pb-5">
    <div class="container p-5">
        <h3 class="open-sans mb-4"><b>Activity Suggestions</b></h3>
        <div style="width:250px;" class="bg-white ps-4 py-3 border border-1 rounded-3">
            <p class="open-sans m-0">Today : <?php echo $date;?></p>
            <p class="open-sans m-0">Weather : <?php echo $weathers['weather']; ?></p>
            <p class="open-sans m-0">Location : <?php echo $location_state; ?></p>
        </div>
    </div>
    <div class="container">
        <?php
        
        require_once '../../controllers/connection.php';

        // today's weather
        $todayweather = $weathers['weather'];
        $state = $weathers['location_state'];

        // random select three from all data where weather is today's weather
        $query = "SELECT * FROM spot_recommendations WHERE weather='$todayweather' AND state='$state' ORDER BY RAND() LIMIT 3";
        $result = mysqli_query($cn,$query);
        $suggestions = mysqli_fetch_all($result, MYSQLI_ASSOC);
        ?>

        <div class="row w-100 d-flex justify-content-center">
            <?php foreach ($suggestions as $suggestion): ?>
                <div class="col-md-4 col-12">
                    <div class="card d-flex flex-column justify-content-between align-items-center w-100" style="min-height: 600px;">
                        <div class="w-100" style="height: 50%;">
                            <img src="<?php echo $suggestion['image']; ?>" style="object-fit: cover;" class="w-100 h-100 card-img-top" alt="...">
                        </div>
                        <div class="card-body p-3 d-flex flex-column justify-content-between" style="height: 50%;">
                            <h5 class="card-title"><?php echo $suggestion['title']; ?></h5>
                            <p class="card-text open-sans"><?php echo $suggestion['description']; ?></p>
                            <a class="btn btn-primary" href="/views/pages/locations.php">Explore More!</a>
                        </div>
                    </div>
                </div>

            <?php endforeach;?>
        </div>
    </div>
</div>

<!-- pie chart -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const hasData = <?php echo json_encode($has_data); ?>;
        if (hasData) {
            Highcharts.chart('container', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'Weather Distribution in Malaysia ~ <?php echo $date;?>'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                        }
                    }
                },
                series: [{
                    name: 'Weather',
                    colorByPoint: true,
                    data: <?php echo $weather_percentages_json; ?>
                }]
            });
        } else {
            document.getElementById('container').innerHTML = '<div class="bg-warning p-5 border rounded-5" style="width:60%; text-align: center; font-size: 1.5em; margin-top: 100px;">Weather Distribution in Malaysia ~ <?php echo $date;?> <br>No data for today</div>';
        }
    });
</script>
<!-- end of chart -->

<script>
    let formEl = document.getElementById('weatherfilter');
    formEl.addEventListener('change', (e) => {
        e.target.parentElement.submit()
    })
</script>

<scri
<?php
    }
    require_once '../templates/layout.php';
?>