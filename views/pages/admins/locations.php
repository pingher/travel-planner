<?php
$title = "Recommendations";

function get_content(){
?>


<div class="container-fluid m-0 p-0" style="background-color: rgb(241, 241, 241); min-height:90vh;">
    <div class="container d-flex justify-content-center align-items-center flex-column" >
        <h3 class="open-sans text-uppercase text-center mt-5 h2">Spot Recommendations</h3>

        <!-- Button trigger modal -->
        <button type="button" class="p-3 rounded-3 border border-1 mt-2 donebtnadd" style="width:300px" data-bs-toggle="modal" data-bs-target="#addnewspot">
        Add New <i class="bi bi-plus-circle-fill"></i>
        </button>

        <!-- Modal -->
        <div class="modal fade" id="addnewspot" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="bi bi-plus-circle-fill"></i> Add New Spots</h1>
                    <button type="button" class="btn-close closeadd" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/controllers/admins/addspot.php" class="d-flex justify-content-center align-items-center flex-column gap-3" method="POST" enctype="multipart/form-data">
                        <!-- image -->
                        <div class="border border-2 p-3 d-flex justify-content-center align-items-center" style="width:65%; height:160px;">
                            <input type="file" class="form-control" name="image"/>
                        </div>

                        <div class="d-flex flex-column gap-3" style="width:65%;">
                            <!-- title -->
                            <div>
                                <label class="open-sans w-100" for="">Title : </label>
                                <input type="text" name="title" class="w-100"/>
                            </div>
                            <!-- description -->
                            <div>
                                <label class="open-sans w-100" for="">Description : </label>
                                <textarea name="description" cols="40" rows="4" class="w-100"></textarea>
                            </div>
                            <!-- price.state.weather -->
                            <div class="border border-2 p-3 d-flex justify-content-center w-100 flex-column gap-3">
                                <!-- price -->
                                <div>
                                    <label class="open-sans">Price : RM</label>
                                    <input type="number" step="0.01" name="price" style="width:66%"/>
                                </div>
                                <!-- state -->
                                <div>
                                    <label class="open-sans">State : </label>
                                    <select name="state" id="" style="width:76%" required>
                                        <option value="Johor">Johor</option>
                                        <option value="Kedah">Kedah</option>
                                        <option value="Kelantan">Kelantan</option>
                                        <option value="Melaka">Melaka</option>
                                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                                        <option value="Pahang">Pahang</option>
                                        <option value="Penang">Penang</option>
                                        <option value="Perak">Perak</option>
                                        <option value="Perlis">Perlis</option>
                                        <option value="Sabah">Sabah</option>
                                        <option value="Sarawak">Sarawak</option>
                                        <option value="Selangor">Selangor</option>
                                        <option value="Terengganu">Terengganu</option>
                                    </select>
                                </div>
                                <!-- weather -->
                                <div>
                                    <label class="open-sans">Weather : </label>
                                    <select name="weather" id="" style="width:66%" required>
                                        <option value="Sunny ☀️">Sunny</option>
                                        <option value="Cloudy 🌥️">Cloudy</option>
                                        <option value="Rainy 🌧️">Rainy</option>
                                    </select>
                                </div>
                                <!--  -->
                            </div>
                            <!-- end of box price state weather --> 
                            <button class="p-3 rounded-2 border border-1 mb-4 donebtnadd" style="letter-spacing:10px;" type="submit">DONE</button>
                        </div>
                        <!-- end of content in form-->
                    </form>
                </div>
                </div>
            </div>
        </div>
        <!--  -->

        <!-- display -->
        <?php
            require_once '../../../controllers/connection.php';
            $query = "SELECT * FROM spot_recommendations;";
            $result_query = mysqli_query($cn, $query);
            $spots = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
        ?>

        <div class="row mt-5 w-100">
            <?php foreach ($spots as $spot): 
                $location_id = $spot['id'];
                $rate_query = "SELECT ratings FROM ratings WHERE location_id = $location_id;";
                $rate_result = mysqli_query($cn, $rate_query);
                $ratings = mysqli_fetch_all($rate_result, MYSQLI_ASSOC);
                
                // Calculate average rating
                if (count($ratings) > 0) {
                    $total_rating = array_sum(array_column($ratings, 'ratings'));
                    $average_rating = $total_rating / count($ratings);
                } else {
                    $average_rating = 0;
                }
                $spot['average_rating'] = $average_rating;   
            ?>
                <div class="col-md-4 col-12 mb-4 d-flex align-items-stretch">
                    <div class="spots bg-white p-4 d-flex flex-column w-100 align-items-center" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editspot<?php echo $spot['id']; ?>"
                        style="height:350px;"
                    >
                        <div class="w-100" style="height:70%; overflow:hidden;">
                            <img class="imgspot w-100 h-100 object-fit-cover" src="<?php echo $spot['image']; ?>" style="object-fit: cover;">
                        </div>
                        <h4 class="open-sans mt-3 p-0 title text-center" style="flex-shrink: 0;"><?php echo $spot['title']; ?></h4>
                        <!-- rating -->
                        <div class="rateyo" data-rateyo-rating="<?php echo $average_rating; ?>" data-rateyo-read-only="true"></div>
                        <p>
                            <?php 
                                if ($spot['average_rating'] != 0) {
                                    echo round($spot['average_rating'], 1);
                                } else {
                                    echo "No ratings yet";
                                }
                            ?>
                        </p>

                    </div>
                </div>

                <!-- Modal for editing -->
                <div class="modal fade" id="editspot<?php echo $spot['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!--  -->
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel"><i class="bi bi-plus-circle-fill"></i> Edit Spot</h1>
                            <button type="button" class="btn-close closeadd" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form action="/controllers/admins/editspot.php" class="d-flex justify-content-center align-items-center flex-column gap-3" method="POST" enctype="multipart/form-data">
                                <!-- id -->
                                <input type="hidden" name="id" value="<?php echo $spot['id']; ?>"/>
                                <!-- image -->
                                <div class="border border-2 p-3 d-flex justify-content-center align-items-center" style="width:65%; height:160px;">
                                    <input type="file" class="form-control" name="image" value="<?php echo $spot['image']; ?>"/>
                                </div>

                                <div class="d-flex flex-column gap-3" style="width:65%;">
                                    <!-- title -->
                                    <div>
                                        <label class="open-sans w-100" for="">Title : </label>
                                        <input type="text" name="title" class="w-100" value="<?php echo $spot['title']; ?>"/>
                                    </div>
                                    <!-- description -->
                                    <div>
                                        <label class="open-sans w-100" for="">Description : </label>
                                        <textarea name="description" cols="40" rows="4" class="w-100"><?php echo $spot['description']; ?></textarea>
                                    </div>
                                    <!-- price.state.weather -->
                                    <div class="border border-2 p-3 d-flex justify-content-center w-100 flex-column gap-3">
                                        <!-- price -->
                                        <div>
                                            <label class="open-sans">Price : RM</label>
                                            <input type="number" step="0.01" name="price" style="width:66%" value="<?php echo $spot['price']; ?>"/>
                                        </div>
                                        <!-- state -->
                                        <div>
                                            <label class="open-sans">State : </label>
                                            <select name="state" id="" style="width:76%" required>
                                                <option value="Johor" <?php if($spot['state'] == 'Johor') echo 'selected'; ?>>Johor</option>
                                                <option value="Kedah" <?php if($spot['state'] == 'Kedah') echo 'selected'; ?>>Kedah</option>
                                                <option value="Kelantan" <?php if($spot['state'] == 'Kelantan') echo 'selected'; ?>>Kelantan</option>
                                                <option value="Melaka" <?php if($spot['state'] == 'Melaka') echo 'selected'; ?>>Melaka</option>
                                                <option value="Negeri Sembilan" <?php if($spot['state'] == 'Negeri Sembilan') echo 'selected'; ?>>Negeri Sembilan</option>
                                                <option value="Pahang" <?php if($spot['state'] == 'Pahang') echo 'selected'; ?>>Pahang</option>
                                                <option value="Penang" <?php if($spot['state'] == 'Penang') echo 'selected'; ?>>Penang</option>
                                                <option value="Perak" <?php if($spot['state'] == 'Perak') echo 'selected'; ?>>Perak</option>
                                                <option value="Perlis" <?php if($spot['state'] == 'Perlis') echo 'selected'; ?>>Perlis</option>
                                                <option value="Sabah" <?php if($spot['state'] == 'Sabah') echo 'selected'; ?>>Sabah</option>
                                                <option value="Sarawak" <?php if($spot['state'] == 'Sarawak') echo 'selected'; ?>>Sarawak</option>
                                                <option value="Selangor" <?php if($spot['state'] == 'Selangor') echo 'selected'; ?>>Selangor</option>
                                                <option value="Terengganu" <?php if($spot['state'] == 'Terengganu') echo 'selected'; ?>>Terengganu</option>
                                            </select>
                                        </div>
                                        <!-- weather -->
                                        <div>
                                            <label class="open-sans">Weather : </label>
                                            <select name="weather" id="" style="width:66%" required>
                                                <option value="Sunny ☀️" <?php if($spot['weather'] == 'Sunny ☀️') echo 'selected'; ?>>Sunny</option>
                                                <option value="Cloudy 🌥️" <?php if($spot['weather'] == 'Cloudy 🌥️') echo 'selected'; ?>>Cloudy</option>
                                                <option value="Rainy 🌧️" <?php if($spot['weather'] == 'Rainy 🌧️') echo 'selected'; ?>>Rainy</option>
                                            </select>
                                        </div>
                                        <!--  -->
                                    </div>
                                    <!-- end of box price state weather --> 
                                    <button class="p-3 rounded-2 border border-1 mb-4 donebtnadd" style="letter-spacing:10px;" type="submit">DONE</button>
                                </div>
                                <!-- end of content in form-->
                            </form>
                        </div>
                        </div>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>
        <!--  -->

    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>
<script>
    $(function () {
        $(".rateyo").each(function () {
            var rating = $(this).attr("data-rateyo-rating");
            $(this).rateYo({
                rating: rating,
                readOnly: true,
                starWidth: "20px"
            });
        });
    });
</script>

<?php
}
require_once '../../templates/layout.php';
?>