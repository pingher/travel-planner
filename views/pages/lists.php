<?php 
    $title = "Lists";

    function get_content(){
        $date = date("Y-m-d");
        $user_info = $_SESSION['user_info'];
        $user_id = $user_info['id'];


        require_once '../../controllers/connection.php';

        // task counts
        $query_today = "SELECT COUNT(id) as task_count FROM togolist WHERE user_id = $user_id AND DATE(created_at) = '$date'";
        $result_today = mysqli_query($cn, $query_today);
        $today_data = mysqli_fetch_assoc($result_today);
        $today = $today_data['task_count'];

        $query_togo = "SELECT COUNT(id) as task_count FROM togolist WHERE user_id = $user_id AND completion_status = 0";
        $result_togo = mysqli_query($cn, $query_togo);
        $togo_data = mysqli_fetch_assoc($result_togo);
        $togo = $togo_data['task_count'];

        $query_done = "SELECT COUNT(id) as task_count FROM togolist WHERE user_id = $user_id AND completion_status = 1";
        $result_done = mysqli_query($cn, $query_done);
        $done_data = mysqli_fetch_assoc($result_done);
        $done = $done_data['task_count'];


        // notes
        $query_notes = "SELECT * FROM notes WHERE user_id = $user_id";
        $notes_result = mysqli_query($cn, $query_notes);
        $notes = mysqli_fetch_all($notes_result, MYSQLI_ASSOC);

        // Filter tasks
        if (isset($_POST['todos'])) {
            $filterchoice = $_POST['todos'];
        } else {
            $filterchoice = 'all';
        }

        if ($filterchoice == 'today') {
            $query = "SELECT * FROM togolist WHERE user_id = $user_id AND DATE(created_at) = '$date' ORDER BY completion_status ASC, id ASC";
        } elseif ($filterchoice == 'oldest') {
            $query = "SELECT * FROM togolist WHERE user_id = $user_id ORDER BY created_at ASC, completion_status ASC, id ASC";
        } elseif ($filterchoice == 'newest') {
            $query = "SELECT * FROM togolist WHERE user_id = $user_id ORDER BY created_at DESC, completion_status ASC, id ASC";
        } elseif ($filterchoice == 'incomplete') {
            $query = "SELECT * FROM togolist WHERE user_id = $user_id AND completion_status = 0 ORDER BY created_at ASC, completion_status ASC, id ASC";
        } elseif ($filterchoice == 'completed') {
            $query = "SELECT * FROM togolist WHERE user_id = $user_id AND completion_status = 1 ORDER BY created_at ASC, completion_status ASC, id ASC";
        } else {
            $query = "SELECT * FROM togolist WHERE user_id = $user_id ORDER BY completion_status ASC, id ASC";
        }

        $result = mysqli_query($cn, $query);
        $lists = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<div class="w-100 listbg">
    <div class="titlelists p-3 py-5">
        <h1 class="pacifico-regular togotitle mb-5">To-Go List ( <?php echo $date?> )</h1>
        <h3 class="open-sans">Today You Have Added <?php echo $today?> Places.</h3>
        <h5 class="open-sans">Only <b class="text-danger"><?php echo $togo?></b> places left! You've visited <b class="text-success"><?php echo $done?></b> places.</h5>
        <p class="open-sans">"New Adventures: Your To-Go List Awaits! 🌈"</p>
    </div>

    <div class="w-100 contentlists p-3">
        <form action="" method="POST" class="filter-todo d-flex gap-2">
            <div class="selecttodo">
                <select name="todos" id="listfilter" class="w-100 p-2 border rounded-5 border-black border-2">
                    <option value="all" <?php if(isset($_POST['todos']) && $_POST['todos'] == 'all') echo 'selected'; ?>>All</option>
                    <option value="today" <?php if(isset($_POST['todos']) && $_POST['todos'] == 'today') echo 'selected'; ?>>Today</option>
                    <option value="oldest" <?php if(isset($_POST['todos']) && $_POST['todos'] == 'oldest') echo 'selected'; ?>>Oldest</option>
                    <option value="newest" <?php if(isset($_POST['todos']) && $_POST['todos'] == 'newest') echo 'selected'; ?>>Newest</option>
                    <option value="incomplete" <?php if(isset($_POST['todos']) && $_POST['todos'] == 'incomplete') echo 'selected'; ?>>Incomplete</option>
                    <option value="completed" <?php if(isset($_POST['todos']) && $_POST['todos'] == 'completed') echo 'selected'; ?>>Completed</option>
                </select>
            </div>
        </form>


        <div class="row w-100">
            <?php foreach($lists as $list): ?>
                <div class="col-md-6 col-12 my-5">
                    <div class="bg-white d-flex flex-row rounded-3 overflow-hidden" style="border:2px solid black;">
                        <div style="width:15%;" class="py-2 border border-1 bg-warning text-white d-flex flex-column gap-2 align-items-center">
                            <!-- DELETING -->
                            <form action="/controllers/list/delete.php" method="POST" class="w-100">
                                <input type="hidden" name="id" value="<?php echo $list['id']; ?>">
                                <button type="submit" class="btn btn-link text-decoration-none text-white">
                                    <i style="font-size:30px;" class="bi bi-trash"></i>
                                </button>
                            </form>

                            <!-- completed? -->
                            <button type="button" class="btn btn-link text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#complete<?php echo $list['id']; ?>">
                                <i style="font-size:30px;" class="bi bi-bookmark-check"></i>
                            </button>

                            <!-- Ask again to confirm completion -->
                            <div class="modal fade" id="complete<?php echo $list['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5 text-black" id="exampleModalLabel">Completed?</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="/controllers/list/complete.php" method="POST" class="w-100">
                                            <input type="hidden" name="id" value="<?php echo $list['id']; ?>">
                                            <button type="submit" class="btn btn-primary text-decoration-none text-white">
                                                Yes
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                        </form>
                                    </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes -->
                            <button type="button" class="btn btn-link text-decoration-none text-white" data-bs-toggle="modal" data-bs-target="#notes<?php echo $list['id']; ?>">
                                <i style="font-size:30px;" class="bi bi-sticky"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="notes<?php echo $list['id']; ?>" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5 text-black">Notes</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Add notes -->
                                            <form action="/controllers/list/notes.php" method="POST" class="w-100">
                                                <input type="hidden" name="togo_id" value="<?php echo $list['id']; ?>">
                                                <div class="mb-3 text-black">
                                                    <label class="form-label">New Note</label>
                                                    <textarea class="form-control border border-black border-2" id="note" name="description" rows="3"></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Add Note +</button>
                                            </form>

                                            <!-- Displaying Notes -->
                                            <div class="mt-3">
                                                <h5 class="text-black">Existing Notes:</h5>
                                                <?php foreach ($notes as $note): ?>
                                                    <?php if ($note['togo_id'] == $list['id']): ?>
                                                        <ul class="text-black">
                                                            <li>
                                                                <div class="border border-1 border-black rounded p-2 mb-2 text-black">
                                                                    <p><?php echo $note['notedescription']; ?></p>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="p-3 d-flex flex-column gap-2 position-relative">
                            <?php if ($list['completion_status'] == 1) : ?>
                                <div class="completed position-absolute w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center active">
                                    <h2 class="open-sans text-white">DONE</h2>
                                </div>
                            <?php else : ?>
                                <div class="completed position-absolute w-100 h-100 top-0 start-0 d-flex justify-content-center align-items-center">
                                    <h2 class="open-sans text-white">DONE</h2>
                                </div>
                            <?php endif; ?>
                            <h3 class="open-sans"><?php echo $list['title'];?></h3>
                            <div class="px-2 py-1 border border-1 rounded-1 border-black" style="width:95%; box-shadow:4px 4px 3px rgba(0,0,0,0.6);">
                                <p class="open-sans m-0"><?php echo $list['description'];?></p>
                            </div>
                            <div class="flex w-100">
                                <small class="text-small open-sans">Created At: <?php echo $list['created_at']?></small>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('listfilter').addEventListener('change', function() {
        this.form.submit();
    });
</script>


<?php
    }
    require_once '../templates/layout.php';
?>
