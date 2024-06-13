<?php
$title = "stories";

function get_content(){    
    require_once '../../controllers/connection.php';
    $user_info = $_SESSION['user_info'];
    $user_id = $user_info['id'];
    $username = $user_info['username'];

    $img_query = "SELECT pic FROM users WHERE id = $user_id;";
    $result_img = mysqli_query($cn, $img_query);
    $img_data = mysqli_fetch_assoc($result_img);

    //author who created the post?


    // get total likes and posts
    $query_posts = "SELECT COUNT(id) as post_count FROM stories WHERE user_id = $user_id AND deleted='0'";
    $result_posts = mysqli_query($cn, $query_posts);
    $posts_data = mysqli_fetch_assoc($result_posts);
    $posts = $posts_data['post_count'];

    //get likes
    // Get total likes
    // story which is created by the log in user
    // count the like of this story except like of user himself
    $query_likes = "SELECT COUNT(likes.id) as likes_count 
                    FROM likes 
                    JOIN stories ON likes.story_id = stories.id 
                    WHERE stories.user_id = $user_id AND likes.user_id != $user_id AND stories.deleted='0'";
                    
    $result_likes = mysqli_query($cn, $query_likes);
    $likes_data = mysqli_fetch_assoc($result_likes);
    $likes = $likes_data['likes_count'];

    //
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $date = date("Y-m-d");

  // Filterssss
    if (isset($_POST['filterstory'])) {
        $filterchoice = $_POST['filterstory'];
    } else {
        $filterchoice = 'all';
    }

    if ($filterchoice == 'today') {
        $query_stories = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username 
                          FROM stories 
                          JOIN users ON stories.user_id = users.id 
                          WHERE DATE(stories.created_at) = '$date' AND stories.deleted = '0'";
    } elseif ($filterchoice == 'oldest') {
        $query_stories = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username 
                          FROM stories 
                          JOIN users ON stories.user_id = users.id 
                          WHERE stories.deleted = '0'
                          ORDER BY stories.created_at ASC";
    } elseif ($filterchoice == 'newest') {
        $query_stories = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username 
                          FROM stories 
                          JOIN users ON stories.user_id = users.id 
                          WHERE stories.deleted = '0'
                          ORDER BY stories.created_at DESC";
    } elseif ($filterchoice == 'mine') {
        $query_stories = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username 
                          FROM stories 
                          JOIN users ON stories.user_id = users.id 
                          WHERE stories.user_id = $user_id AND stories.deleted = '0'";
    } elseif ($filterchoice == 'mostlikes') {
        // need three tables, stories & users & likes
        // story and user - user_id & id
        // story and likes - id & story_id
        // group by column name (story id) - to calculate likes of each story
        // order most likes
        $query_stories = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username, COUNT(likes.id) as likes_count 
        FROM stories 
        JOIN users ON stories.user_id = users.id 
        LEFT JOIN likes ON stories.id = likes.story_id 
        WHERE stories.deleted = '0'
        GROUP BY stories.id ORDER BY likes_count DESC ";
    } else {
        $query_stories = "SELECT stories.id, stories.title, stories.description, stories.photo, stories.created_at, users.username 
                          FROM stories 
                          JOIN users ON stories.user_id = users.id
                          WHERE stories.deleted = '0'";
    }


    $result_stories = mysqli_query($cn, $query_stories);
    $stories = mysqli_fetch_all($result_stories, MYSQLI_ASSOC);

?>


<h1 class="pacifico-regular text-black display-5 storiestitle m-0 p-4">Travel Stories</h1>

<!-- profile -->
<div class="container-fluid">
    <div class="row">   
        <div class="col-md-6 p-4  d-flex justify-content-end">

            <div class="d-flex justify-content-center align-items-center flex-column">
                <!-- img should display in here and the form should hide when there is an image -->
                <!-- Check if there is an image available -->
                <!-- if yes there is image -->

                <?php if ($img_data && $img_data['pic']): ?> 
                    <!-- Display the image -->
                    <div class="m-0 px-3 pt-3 profileframe d-flex justify-content-center flex-column">
                        <div class="w-100 h-100 picsurface" style="position:relative;">
                            <div class="blacksurface w-100 h-100 "></div>
                            <img class="profilepic rounded-2 w-100 h-100" src="<?php echo $img_data['pic']; ?>">

                            <!-- editing when hover -->
                            <div class="editform w-100">
                                <form action="../../controllers/profile/addprofile.php" method="POST" enctype="multipart/form-data" class="w-100 d-flex justify-content-center align-items-center flex-column">
                                    <input type="file" class="inputstory form-control mb-1" name="image" accept="image/*">
                                    <button class="changepic btn btn-sm btn-warning" type="submit">Change Picture</button>
                                </form>
                            </div>

                        </div>
                    </div>
                    <div class=" usernamefield m-0 ">
                        <h4 class="open-sans usernamestories py-2 text-white text-center w-100"><?php echo $username ?></h4>
                    </div>

                <?php else: ?>
                    <!-- Display the form to upload an image -->
                    <!-- No image -->
                    <form action="../../controllers/profile/addprofile.php" class=" d-flex justify-content-center align-items-center flex-column" method="POST" enctype="multipart/form-data">
                        <div class="bg-white p-4 profileframe d-flex justify-content-center flex-column">
                            <h6 class="open-sans text-danger">- No Profile Picture -</h6>
                            <input type="file" class="form-control mb-3" id="storyImage" name="image" accept="image/*">
                            <button class="btn btn-primary btn-sm" type="submit">Submit</button>
                        </div>
                        <div class=" usernamefield m-0 ">
                            <h3 class="open-sans usernamestories py-2 text-center text-white w-100"><?php echo $username ?></h3>
                        </div>
                    </form>
                <?php endif; ?>

            </div>
        </div>

        <div class="col-md-6 p-4 d-flex align-items-center">
            <div class="storybox rounded-3 d-flex justify-content-center px-5 flex-column border border-3 border-black" style="min-height: 350px; width: 300px">
                <h3 class="open-sans mb-4">Posts : <?php echo $posts; ?></h3>
                <h3 class="open-sans mb-4">Likes : <?php echo $likes; ?></h3>
                
                <!-- add your own new stories part -->
                <!-- Add Story Button -->
                <a href="#" class="btn btn-primary open-sans w-100 p-2 border rounded-3" data-bs-toggle="modal" data-bs-target="#addStoryModal">Add Story +</a>

                <!-- Modal Structure -->
                <div class="modal fade" id="addStoryModal" tabindex="-1" aria-labelledby="addStoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addStoryModalLabel">Add New Story</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                        <div class="modal-body">
                            <form id="addStoryForm" action="../../controllers/stories/addstories.php" method="POST" enctype="multipart/form-data">
                                <div class="mb-3">
                                    <label for="storyTitle" class="form-label">Title</label>
                                    <input type="text" class="form-control" name="title" required>
                                </div>
                                <div class="mb-3">
                                    <label for="storyDescription" class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="storyImage" class="form-label">Upload Image</label>
                                    <input type="file" class="form-control" name="image" accept="image/*" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Add Story</button>
                            </form>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- displayinngggggg -->
<div class="container mx-auto" style="margin-bottom: 100px;padding-top: 100px;">
    <!-- filter -->
    <form action="" style="margin-bottom:50px; width:25rem" method="POST">
            <select name="filterstory" id="storyfilter" class="w-50 p-2 border rounded-5 border-black border-2 bg-warning">
                <option value="all">All</option>
                <option value="today">Today</option>
                <option value="oldest">Oldest</option>
                <option value="newest">Newest</option>
                <option value="mine">My Posts</option>
                <option value="mostlikes">Most Likes</option>
            </select>
    </form>


    <div class="row">
        <?php if (!empty($stories)) : ?>
            <?php foreach ($stories as $story) : ?>
                <div class="col-md-4 d-flex justify-content-center mb-3">
                    <div class="card cardstory" style="width: 25rem; min-height: 500px;">
                        <img src="<?php echo $story['photo']; ?>" class="card-img-top" style="width: 100%; height: 250px; object-fit: cover;" alt="Story Image">
                        <div class="card-body position-relative">
                            <h5 class="card-title open-sans"><b><?php echo $story['title']; ?></b></h5>
                            <p class="card-text open-sans"><?php echo $story['description']; ?></p>
                            <p class="card-text m-0"><small class="text-muted"><?php echo date("F j, Y, g:i a", strtotime($story['created_at'])); ?></small></p>
                            <p class="card-text"><small class="text-muted">By <b><?php echo $story['username']; ?></b></small></p>
                            

                            <!-- likes start -->
                            <div class="d-flex gap-2 position-absolute bottom-0 end-0 m-2">
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

                                <?php if (isset($_SESSION['user_info'])): ?>
                                    <a class="text-decoration-none h3" href="../../controllers/likes/like.php?id=<?php echo $story['id'] ?>">
                                        <?php
                                            $like_unlike = "🤍";
                                            if (!empty($likes)) {
                                                foreach ($likes as $like) {
                                                    if ($like['user_id'] == $_SESSION['user_info']['id']) {
                                                        $like_unlike = "❤️‍🔥";
                                                        break;
                                                    }
                                                }
                                            }
                                            echo $like_unlike;
                                        ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <!-- Likes End -->

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="open-sans h5">No stories found.</p>
        <?php endif; ?>

        <?php mysqli_close($cn); ?>
    </div>
    
</div>

<script>
    let formEl = document.getElementById('storyfilter');
    formEl.addEventListener('change', (e) => {
        e.target.parentElement.submit()
    })
</script>

<?php
}
require_once '../templates/layout.php';
?>
