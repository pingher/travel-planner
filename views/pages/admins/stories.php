<?php 
$title = "Stories";

function get_content() {
    require_once '../../../controllers/connection.php';

    $query = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username 
              FROM stories 
              JOIN users ON stories.user_id = users.id
              WHERE stories.deleted = 0";
    $result = mysqli_query($cn, $query);
    $stories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    $user_info = $_SESSION['user_info'];
    $user_id = $user_info['id'];
    $username = $user_info['username'];

    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date = date("Y-m-d");
?>

<div class="container py-5">
    <div class="row">
        <?php if (!empty($stories)) : ?>
            <?php foreach ($stories as $story) : ?>
                <div class="col-md-4 d-flex justify-content-center mb-3" data-bs-toggle="modal" data-bs-target="#deleting<?php echo $story['id']; ?>">
                    <div class="card cardstory" style="width: 25rem; min-height: 500px;">
                        <img src="<?php echo $story['photo']; ?>" class="card-img-top" style="width: 100%; height: 250px; object-fit: cover;" alt="Story Image">
                        <div class="card-body position-relative">
                            <h5 class="card-title open-sans"><b><?php echo $story['title']; ?></b></h5>
                            <p class="card-text open-sans"><?php echo $story['description']; ?></p>
                            <p class="card-text m-0"><small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($story['created_at'])); ?></small></p>
                            <p class="card-text"><small class="text-muted">By <b><?php echo $story['username']; ?></b></small></p>
                            

                            <!-- likes start -->
                            <div class="d-flex gap-2 position-absolute bottom-0 end-0 m-2"> Likes : 
                                <span class="badge bg-dark text-white h3 p-2 px-3 d-flex justify-content-center align-items-center">
                                    <?php 
                                        $story_id = $story['id'];
                                        $likes_query = "SELECT * FROM likes WHERE story_id = $story_id;";
                                        $likes_result = mysqli_query($cn, $likes_query);

                                        if ($likes_result) {
                                            $likes = mysqli_fetch_all($likes_result, MYSQLI_ASSOC);
                                            echo count($likes);
                                        } else {
                                            echo "0";
                                        }
                                    ?>
                                </span>
                            </div>
                            <!-- Likes End -->

                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="deleting<?php echo $story['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm to Delete?</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <form action="/controllers/admins/deletestory.php" method="POST">
                                <input type="hidden" name="id" value="<?php echo $story['id']; ?>">
                                <button type="submit" class="btn btn-primary">Yes</button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->

            <?php endforeach; ?>
        <?php else : ?>
            <p class="open-sans h5">No stories found.</p>
        <?php endif; ?>
        <?php mysqli_close($cn); ?>
    </div>
</div>

<?php
}
require_once '../../templates/layout.php';
?>
