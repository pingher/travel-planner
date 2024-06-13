<?php
$title = "Admin Weather";

function get_content(){
    $states = ['Johor', 'Kedah', 'Kelantan', 'Melaka', 'Negeri Sembilan', 'Pahang', 'Penang', 'Perak', 'Perlis', 'Sabah', 'Sarawak', 'Selangor', 'Terengganu'];
    $weathers = ['Sunny ☀️','Cloudy 🌥️', 'Rainy 🌧️'];
    date_default_timezone_set('Asia/Kuala_Lumpur');

    // get tmr's date
    $date = new DateTime();
    $current_date = $date->format('Y-m-d');
    $date->modify('+1 day');
    $new_date = $date->format('Y-m-d');

?>

<div class="container weatheradmin">

    <button class="btn btn-info" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample" style="margin-top: 100px; width: 40vh; height:10vh;">
        Insert Data Weather Prediction
    </button>

    <div class="border border-3 border-black collapse" id="collapseExample" style="padding: 50px; padding-left:100px; padding-right:100px; margin-top: 10px;">
        <form action="../../../controllers/admins/weather_process.php" method="POST" class="">
            <h3 class="open-sans" style="margin-bottom:30px">Weather Prediction Data (<?php echo $new_date;?>)</h3>
            <input type="hidden" name="date" value="<?php echo $new_date; ?>">
            <div class="d-flex justify-content-between">
                <?php foreach ($weathers as $weather): ?> 
                    <!-- looping the form -->
                    <div class="weather-section" data-weather="<?php echo $weather; ?>">
                        <h3 class="open-sans"><?php echo $weather; ?></h3>
                        <?php foreach ($states as $state): ?>
                            <!-- looping the states -->
                            <!-- GIVE AN ID -->
                            <!-- label need to link to input aaaa can use for -->
                            <div class="d-flex align-items-center mb-1">
                            <input 
                                style="width:20px; height:20px;" 
                                type="checkbox" 
                                id="<?php echo $weather . '-' . $state; ?>" 
                                class="checkbox" 
                                name="weather[<?php echo $weather; ?>][]" 
                                value="<?php echo $state; ?>" 
                                data-state="<?php echo $state; ?>" 
                                data-weather="<?php echo $weather; ?>"
                            />
                            <label class="open-sans text-center ms-3" for="<?php echo $weather . '-' . $state; ?>"><?php echo $state; ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-5">Submit</button>
        </form>
    </div>

    <!-- filtering -->
<?php
    require_once '../../../controllers/connection.php';

    // Set filter choice
    $filterchoice = isset($_POST['filterweather']) ? $_POST['filterweather'] : 'newest';

    if ($filterchoice == 'oldest') {
        $query_weather = "SELECT * FROM weather ORDER BY weather.date ASC";
    } 
    if ($filterchoice == 'newest') {
        $query_weather = "SELECT * FROM weather ORDER BY weather.date DESC";
    }
    if ($filterchoice == 'johor') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Johor'";
    }
    if ($filterchoice == 'kedah') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Kedah'";
    }
    if ($filterchoice == 'kelantan') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Kelantan'";
    }
    if ($filterchoice == 'melaka') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Melaka'";
    }
    if ($filterchoice == 'sembilan') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Negeri Sembilan'";
    }
    if ($filterchoice == 'pahang') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Pahang'";
    }
    if ($filterchoice == 'penang') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Penang'";
    }
    if ($filterchoice == 'perak') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Perak'";
    }
    if ($filterchoice == 'perlis') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Perlis'";
    }
    if ($filterchoice == 'sabah') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Sabah'";
    }
    if ($filterchoice == 'sarawak') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Sarawak'";
    }
    if ($filterchoice == 'selangor') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Selangor'";
    }
    if ($filterchoice == 'terengganu') {
        $query_weather = "SELECT * FROM weather WHERE location_state='Terengganu'";
    }

    $result_weather = mysqli_query($cn, $query_weather);
    $weathers = mysqli_fetch_all($result_weather, MYSQLI_ASSOC);
?>

<form action="" class="mt-5" style="width:28rem" method="POST">
    <select name="filterweather" id="weatherfilter" class="w-50 p-2 border rounded-5 border-primary border-1 bg-primary text-white">
        <option value="newest" <?php if($filterchoice == 'newest') echo 'selected'; ?>>Newest</option>
        <option value="oldest" <?php if($filterchoice == 'oldest') echo 'selected'; ?>>Oldest</option>
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

<div>
    <table class="table table-bordered table-striped mt-3">
        <thead>
            <tr>
                <th>Date</th>
                <th>State</th>
                <th>Weather</th>
                <th>Temperature</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($weathers as $weather): ?>
                <tr>
                    <td><?php echo $weather['date'] ?></td>
                    <td><?php echo $weather['location_state'] ?></td>
                    <td><?php echo $weather['weather'] ?></td>
                    <td>
                        <form action="/controllers/admins/weathertemp.php" method="POST">
                            <input type="hidden" name="weatherid" value="<?php echo $weather['id']?>">
                            <label for="">Temp: </label>
                            <input 
                            type="number" 
                            name="temperature" 
                            placeholder="°C" 
                            class="temperature-input"
                            value="<?php echo ($weather['temperature'] != 0) ? $weather['temperature'] : '' ?>"
                            />
                        </form>
                    </td>
                    <td>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#weatheredit<?php echo $weather['id']; ?>">
                            <i class="bi bi-pencil-square"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="weatheredit<?php echo $weather['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel<?php echo $weather['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel<?php echo $weather['id']; ?>">Edit Weather Data</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/controllers/admins/editweather.php" method="POST" class="d-flex gap-2 flex-column">
                                            <input type="hidden" value="<?php echo $weather['id']?>" name="id">
                                            <div>
                                                <label for="">Date :</label>
                                                <input type="text" value="<?php echo $weather['date']?>" disabled />
                                            </div>
                                            <div>
                                                <label for="">Location :</label>
                                                <input type="text" value="<?php echo $weather['location_state']?>" disabled />
                                            </div>
                                            <div>
                                                <label for="">Weather :</label>
                                                <select name="weather">
                                                    <?php foreach (['Sunny ☀️','Cloudy 🌥️', 'Rainy 🌧️'] as $option): ?>
                                                        <option value="<?php echo $option; ?>" <?php echo ($weather['weather'] == $option) ? 'selected' : ''; ?>><?php echo $option; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="">Temperature :</label>
                                                <input type="number" name="temperature" value="<?php echo $weather['temperature']; ?>" placeholder="°C" />
                                            </div>
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


</div>

<script>
    // need to know that checkbox's state and weather
    // if state is same and not same weather, then strikethrough
    document.addEventListener('DOMContentLoaded', function() {
        // select all checkbox first.
        const allCheckbox = document.querySelectorAll('.checkbox');

        // can use the 'change' thingy
        allCheckbox.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const state = this.getAttribute('data-state');
                const weather = this.getAttribute('data-weather');
                const isChecked = this.checked;

                // SELECT ALL THAT have same state but different weather
                // HELP IDK HOW
                const others = document.querySelectorAll('.checkbox[data-state="' + state + '"]:not([data-weather="' + weather + '"])');
                others.forEach(function(others) {
                    others.checked = false;
                    others.disabled = isChecked;
                    const label = others.nextElementSibling;
                    if (isChecked) {
                        label.style.textDecoration = 'line-through';
                    } else {
                        label.style.textDecoration = 'none';
                    }
                });
            });
        });
    });
</script>

<script>
    let formEl = document.getElementById('weatherfilter');
    formEl.addEventListener('change', (e) => {
        e.target.parentElement.submit()
    })
</script>

<script>
    document.querySelectorAll('.temperature-input').forEach(input => {
        input.addEventListener('blur', (e) => {
            e.target.form.submit();
        });

        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                e.target.form.submit();
            }
        });
    });

    document.getElementById('weatherfilter').addEventListener('change', (e) => {
        e.target.form.submit();
    });
</script>


<?php
}
require_once '../../templates/layout.php';
?>
