<?php 
include 'db_con.php';

// Check if ID is set to determine if it's editing an existing task or adding a new one
if(isset($_GET['id'])){
    // Editing existing task
    $qry = $conn->query("SELECT * FROM task_list where id = ".$_GET['id'])->fetch_array();
    foreach($qry as $k => $v){
        $$k = $v;
    }
} else {
    // Adding new task
    // Initialize variables or set default values
    $id = '';
    $task = '';
    $description = '';
    $status = 1; // Default status (Pending)
}
?>
<div class="container-fluid">
    <form action="" id="manage-task">
        <!-- Keep the ID field hidden -->
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <!-- Input fields for task details -->
        <input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
        <div class="form-group">
            <label for="">Task</label>
            <input type="text" class="form-control form-control-sm" name="task" value="<?php echo isset($task) ? $task : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
                <?php echo isset($description) ? $description : '' ?>
            </textarea>
        </div>
        <div class="form-group">
            <label for="">Status</label>
            <select name="status" id="status" class="custom-select custom-select-sm">
                <!-- Populate options for status -->
                <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>Pending</option>
                <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>On-Progress</option>
                <option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>Done</option>
            </select>
        </div>
        <!-- Submit button -->
        <button type="submit" class="btn btn-primary">Save Task</button>
    </form>
</div>

<script>
    $(document).ready(function(){
        // Initialize Summernote editor
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        });

        // Handle form submission
        $('#manage-task').submit(function(e){
            e.preventDefault();
            start_load();
            // Submit form data via AJAX
            $.ajax({
                url:'ajax.php?action=save_task',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(resp){
                    if(resp == 1){
                        // Task saved successfully
                        alert_toast('Data successfully saved', 'success');
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    } else {
                        // Failed to save task
                        alert_toast('Failed to save data', 'error');
                    }
                }
            });
        });
    });
</script>
