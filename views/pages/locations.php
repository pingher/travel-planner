<?php 
$title = "Spot Recommendations";
function get_content(){
?>

<div class="container-fluid m-0 p-0" style="background-color: rgb(241, 241, 241); min-height:90vh;">
    <div class="container d-flex justify-content-center align-items-center flex-column">
        <h3 class="open-sans text-uppercase text-center mt-5 h2">Spot Recommendations</h3>

        <!-- Display Spot Recommendations -->
        <?php
            require_once '../../controllers/connection.php';
            $user_id = $_SESSION['user_info']['id'];
            $query = "SELECT * FROM spot_recommendations;";
            $result_query = mysqli_query($cn, $query);
            $spots = mysqli_fetch_all($result_query, MYSQLI_ASSOC);
        ?>

        <div class="row mt-5">
            <?php 
            foreach ($spots as $spot): 
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

                // TO DISPLAY THE USER RATINGS
                $rated_query = "SELECT ratings FROM ratings WHERE location_id = $location_id AND user_id = $user_id;";
                $rated_result = mysqli_query($cn, $rated_query);
                $user_rating = mysqli_fetch_assoc($rated_result);

                if ($user_rating) {
                    $spot['user_rating'] = $user_rating['ratings'];
                } else {
                    $spot['user_rating'] = 0;
                }?>
                <div class="col-md-4 col-12 mb-4 d-flex align-items-stretch">
                    <div class="spots bg-white p-4 d-flex flex-column w-100 align-items-center" 
                        data-bs-toggle="modal" 
                        data-bs-target="#details<?php echo $spot['id']; ?>"
                        style="height:350px;"
                    >
                        <div class="w-100" style="height:70%; overflow:hidden;">
                            <img class="imgspot w-100 h-100 object-fit-cover" src="<?php echo $spot['image']; ?>" style="object-fit: cover;">
                        </div>
                        <h4 class="open-sans mt-3 p-0 title text-center" style="flex-shrink: 0;"><?php echo $spot['title']; ?></h4>
                        <!-- rating -->
                        <div class="rateyo" data-id="<?php echo $spot['id']; ?>" data-rateyo-rating="<?php echo $spot['user_rating'] ?>"></div>
                    </div>
                </div>


                <!-- MODAL - FOR DETAILS -->
                <div class="modal fade" id="details<?php echo $spot['id']; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">Details</h1>
                            <button type="button" class="btn-close closeadd" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-column align-items-center px-5 py-3">
                            <img class="img-fluid w-100 mb-5" src="<?php echo $spot['image']?>" alt="">
                            <p class="open-sans h3"><?php echo $spot['title']?></p>
                            <p class="open-sans text-center mb-5"><?php echo $spot['description']?></p>
                            <div class="rounded-2 bg-primary text-white px-5 py-4 d-flex flex-column justify-content-center gap-2 detail mb-4">
                                <p class="m-0 h5 open-sans">Suitable Weather : <b><?php echo $spot['weather']?></b></p>
                                <p class="m-0 h5 open-sans">Location : <b><?php echo $spot['state']?></b></p>
                                <p class="m-0 h5 open-sans">Price (if ticket) RM : <b><?php echo $spot['price']?></b></p>
                            </div>
                            <!-- average rating -->
                            <p class="open-sans h4 mb-5">Average Rating: <?php echo round($spot['average_rating'], 1); ?></p>
                            <!-- Add togo -->
                            <form action="/controllers/list/addlist.php" method="post" class="w-100">
                                <input type="hidden" name="spot_id" value="<?php echo $spot['id']; ?>">
                                <input type="hidden" name="description" value="<?php echo $spot['description'];?>">
                                <input type="hidden" name="title" value="<?php echo $spot['title'];?>">
                                <button type="submit" class="btn w-100 donebtnadd py-3 mb-5 border border-2">Add TOGO list +</button>
                            </form>

                        </div>
                        </div>
                    </div>
                </div>
                
            <?php endforeach; ?>
        </div>


    </div>
</div>

<!-- Include jQuery and RateYo -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>


<script>
    $(function () {
        $(".rateyo").each(function () {
            var rating = $(this).data("rateyo-rating");
            $(this).rateYo({
                rating: rating,
                starWidth: "20px"
            }).on("rateyo.set", function (e, data) {
                var spotId = $(this).data("id");
                var rating = data.rating;
                
                // Send rating to backend
                $.ajax({
                    url: "/controllers/ratings/addrating.php",
                    type: "POST",
                    data: {
                        spot_id: spotId,
                        rating: rating
                    },
                    success: function (response) {
                        alert("Rating saved successfully!");
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Failed to save rating: " + textStatus + ": " + errorThrown);
                    }
                });
            });
        });
    });
</script>



<?php
}
require_once '../templates/layout.php';
?>
