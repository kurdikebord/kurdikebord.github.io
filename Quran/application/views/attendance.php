<div class="row">
<div class="col-lg-12">
    <h2 class="title-1 m-b-25">Daily Attendance</h2>
    <div class="table-responsive table--no-card m-b-40">
        <table class="table table-borderless table-striped table-earning">
            <thead>
            <tr>
                <th>Date</th>
                <th>User ID</th>
                <th class="text-right">Course</th>
                <th class="text-right">Course ID</th>
                <th class="text-right">Teacher Name</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($attendance_result as $rows){
                echo '
                    <tr>
                        <td>'.$rows['date_time'].'</td>
                        <td>'.$rows['user_id'].'</td>
                        <td class="text-right">'.$rows['course'].'</td>
                        <td class="text-right">'.$rows['course_id'].'</td>
                        <td class="text-right">'.$rows['teacher_name'].'</td>
                    </tr>
                ';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
</div>