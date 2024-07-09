<!--Date: June 29, 2024 - 9:41pm start
    Description: Front-end of the Manage Goals Page for the Project
    Edited: July 2 & 3 2024
            July 4 2024 by Back End Team (BET) - Create Goals Window and Manage Goals Table Functionalities
            July 5 2024 by BET - Archive Feature
    Comments of the Developer:  Read comments. Functionalities will fail to work indeed until they are connected to a backend API that handles them-->

    <?php 
    include('../php/db.php');
    include('../php/anti-shortcut_ssd.php');
    include('../php/department-autofill.php');?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Goals</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@400&display=swap');

        body {
            font-family: Arial, sans-serif;
            background-color: #fff;
            margin: 0;
            padding: 0;
        }

        .header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 30px 28px;
            background-color: transparent;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            gap: 10px;
        }

        .header button {
            margin-left: 18px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #5A4ABD, #78909C);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        .header button:hover {
            background: #D9E4F5;
            color: #4a3bb3
        }

        .logout {
            margin-left: 40px;
            margin-right: 62.5px;
            color: inherit;
            text-decoration: none;
            font-size: 16px;
            cursor: pointer;
        }

        .logout:hover {
            color: #4a3bb3;
        }

        .goal-title {
            font-family: 'Lato', sans-serif;
            color: black;
            font-size: 18px;
            font-weight: 300;
            margin: 20px 28px;
        }

        table {
            width: calc(100% - 56px);
            border-collapse: collapse;
            margin: 20px 28px;
            font-family: 'Lato', sans-serif;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        tr {
            width: 30px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .medium-style {
            background-color: #ddebf7;
        }

        .medium-style2 {
            background-color: #D1D1D1;
        }

        .input-bar {
            width: 30%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
        }

        .search-button, .create-button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #595B9B;
            color: white;
            cursor: pointer;
            font-size: 14px;
            font-weight: 300;
            transition: background-color 0.3s; /* Smooth transition */
        }

        button:hover {
        background-color: #0056b3; /* Darken color on hover */
        }

        .create-button {
            margin-left: 160px;
        }

        .edit-button, .archive-button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            background-color: #5A4ABD;
            color: white;
            cursor: pointer;
            font-size: 12px;
            margin-right: 3px;
        }

        .view-button, .copy-button, .ok-button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            background-color: #5A4ABD;
            color: white;
            cursor: pointer;
            font-size: 12px;
            margin-right: 3px;
            display: inline-block;
        }

        .row-1 td:first-child {
            background-color: #FEFEFE;
        }

        .row-2 td:first-child {
            background-color: #e6ebfc;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .modal-content h2 {
            width: 100%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-input {
            width: calc(48% - 10px);
            padding: 10px;
            margin: 5px 0;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
        }

        .form-select {
            width: calc(48% - 10px);
            padding: 10px;
            margin: 5px 0;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: white;
        }

        .form-full {
            width: 100%;
        }

        .kpi-text {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #f4f4f4;
            color: #333;
        }

        .save-button-container {
            width: 100%;
            text-align: right;
        }

        /* Popup container - can be anything you want */
        .popup {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        /* Popup content */
        .popup-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 30%; /* Could be more or less, depending on screen size */
        }

        /* Close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <div class="header">
            <button onclick="location.href='ManageGoals.php'">Manage Goals</button>
            <button onclick="location.href='ManageActionPlans.html'">Manage AP</button>
            <button onclick="location.href='ViewReports.html'">View Reports</button>
            <form method="post" class="logout">
                    <button type="submit" name="logout" class="logout-button">Log Out</button>
            </form>
            <!--
            <div class="logout">
                <a href="#" class="logout">Logout</a>
            </div>
    -->
        </div>
    </header>

    <div class="goal-title">Sabbath School Goals</div>

    <table>
        <tr>
            <td colspan="5" class="medium-style">
                <input type="text" class="input-bar" placeholder="Search...">
                <button class="search-button">Search</button>
                <button class="create-button" id="createGoalBtn">Create New Goal</button>
            </td>
        </tr>
        <tr>
            <td colspan="5" class="medium-style"></td>
        </tr>
        <tr>
            <th class="medium-style">Current Goals</th>
            <th class="medium-style">Initiative</th>
            <th class="medium-style">Target</th>
            <th class="medium-style">Budget</th>
            <th class="medium-style">Actions</th>
        </tr>
        <?php
 $sql = "SELECT id, title, initiative, targets, total_budget FROM goal WHERE archived IS NULL";
 $result = $conn->query($sql);

 if ($result->num_rows > 0) {
     $rowClass = "row-1";
     while ($row = $result->fetch_assoc()) {
         echo "<tr class=\"$rowClass\">";
         echo "<td>{$row['title']}</td>";
         echo "<td>{$row['initiative']}</td>";
         echo "<td>{$row['targets']}</td>";
         echo "<td>{$row['total_budget']}</td>";
         echo '<td>
                 <button class="view-button" data-goal-id="' . $row['id'] . '">View</button>
                 <button class="edit-button">Edit</button>
                 <form method="post" action="../php/archive_goal.php" style="display:inline;" onsubmit="return confirmArchive(\'' . $row['title'] . '\');">
                     <input type="hidden" name="goal_id" value="' . $row['id'] . '">
                     <input type="hidden" name="goal_title" value="' . $row['title'] . '">
                     <button type="submit" class="archive-button">Archive</button>
                 </form>
               </td>';
         echo '</tr>';
         $rowClass = ($rowClass == "row-1") ? "row-2" : "row-1";
     }
 } else {
     echo "<tr><td colspan='5'>No goals found.</td></tr>";
 }
 ?>
</table>

<div class="previous-goals-section">
        <div class="toggle-table">
            <span id="toggleChevron" class="chevron" onclick="toggleTable()">▶</span> 
        </div>
        <table id="previousGoalsTable" style="display: none;">
            <tr>
                <th class="medium-style2">Previous Goals</th>
                <th class="medium-style2">Initiative</th>
                <th class="medium-style2">Target</th>
                <th class="medium-style2">Budget</th>
                <th class="medium-style2">Actions</th>
            </tr>
            <?php
            // SQL query to fetch unarchived goals from previous years under the user's department
            $sql = "SELECT id, title, initiative, targets, total_budget FROM goal WHERE archived IS NULL AND department = ? AND year < ? ORDER BY year DESC";
            $stmt_goals = $conn->prepare($sql);

            // Bind parameters
            $department = 'YourDepartment'; // replace with actual department variable
            $current_year = date('Y');
            $stmt_goals->bind_param("si", $department, $current_year);

            // Execute the statement
            $stmt_goals->execute();

            // Get result set
            $result = $stmt_goals->get_result();

            if ($result->num_rows > 0) {
                $rowClass = "row-1";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr class=\"$rowClass\">";
                    echo "<td>{$row['title']}</td>";
                    echo "<td>{$row['initiative']}</td>";
                    echo "<td>{$row['targets']}</td>";
                    echo "<td>{$row['total_budget']}</td>";
                    echo '<td>
                             <button class="view-button" data-goal-id="' . $row['id'] . '">View</button>
                            <button class="copy-button">Copy</button>
                            <form method="post" action="../php/archive_goal.php" style="display:inline" onsubmit="return confirmArchive(\'' . $row['title'] . '\');">
                                <input type="hidden" name="goal_id" value="' . $row['id'] . '">
                                <input type="hidden" name="goal_title" value="' . $row['title'] . '">
                                <button type="submit" class="archive-button">Archive</button>
                            </form>
                        </td>';
                    echo '</tr>';
                    $rowClass = ($rowClass == "row-1") ? "row-2" : "row-1";
                }
            } else {
                echo "<tr><td colspan='5'>No goals found.</td></tr>";
            }

            // Close the statement
            $stmt_goals->close();
            ?>
        </table>
    </div>

<script>
    function toggleTable() {
        var table = document.getElementById("previousGoalsTable");
        var chevron = document.getElementById("toggleChevron");

        if (table.style.display === "none") {
            table.style.display = "table";
            chevron.textContent = "▼";
        } else {
            table.style.display = "none";
            chevron.textContent = "▶";
        }
    }
</script>

<script>
    // Function to simulate viewing a goal (replace with actual functionality later)
    function viewGoal(goalName) {
        alert("Viewing " + goalName);
        // Implement actual view functionality when backend is integrated
    }

    // Function to simulate editing a goal (replace with actual functionality later)
    function editGoal(goalName) {
        alert("Editing " + goalName);
        // Implement actual edit functionality when backend is integrated
    }

    // Function to simulate copying a goal (replace with actual functionality later)
    function copyGoal(goalName) {
        alert("Copying " + goalName);
        // Implement actual copy functionality when backend is integrated
    }

    // Function to simulate archiving a goal (replace with actual functionality later)
    function archiveGoal(goalName) {
        alert("Archiving " + goalName);
        // Implement actual archive functionality when backend is integrated
    }

    // Function to simulate searching for a goal (replace with actual functionality later)
    function searchGoal() {
        var searchQuery = document.getElementById("searchInput").value;
        alert("Searching for " + searchQuery);
        // Implement actual search functionality when backend is integrated
    }
</script>

    <!-- The Archive Dialog -->
    <div id="myModal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Archive Confirmation</h2>
            <p>Are you sure you want to archive this goal?</p>
            <button id="confirmArchiveBtn">Yes</button>
            <button id="cancelArchiveBtn">No</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the modal
            var modal = document.getElementById("myModal");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // Get the buttons that open the modal
            var archiveButtons = document.getElementsByClassName("archive-button");

            // When the user clicks on the button, open the modal
            Array.from(archiveButtons).forEach(function(button) {
                button.onclick = function() {
                    modal.style.display = "block";
                };
            });

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            };

            // When the user clicks on "No" button, close the modal
            document.getElementById("cancelArchiveBtn").onclick = function() {
                modal.style.display = "none";
            };

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            // Handle the archive confirmation
            document.getElementById("confirmArchiveBtn").onclick = function() {
                // Here you would add your AJAX call to archive the goal
                alert("Goal archived!"); // Placeholder for actual logic
                modal.style.display = "none";
            };
        });
    </script>

            <!-- View Goal Modal -->
        <div id="viewGoalModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Goal Details</h2>
                <div id="goalDetails"></div>
                <button class="ok-button" id="okButton">OK</button>
            </div>
        </div>

    <script>
       document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById("viewGoalModal");
    var span = modal.getElementsByClassName("close")[0];
    var okButton = document.getElementById("okButton");

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks on "OK" button, close the modal
    okButton.onclick = function() {
        modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    document.querySelectorAll('.view-button').forEach(button => {
        button.addEventListener('click', function() {
            const goalId = this.getAttribute('data-goal-id');
            fetchGoalDetails(goalId);
        });
    });

    function fetchGoalDetails(goalId) {
        console.log("Fetching details for goal ID:", goalId); // Add this line for debugging
        fetch(`../php/view_goal.php?id=${goalId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    displayGoalDetails(data);
                    modal.style.display = "block";
                }
            })
            .catch(error => console.error('Error fetching goal details:', error));
    }

    function displayGoalDetails(goal) {
        var details = `
            <p><strong>Title:</strong> ${goal.title}</p>
            <p><strong>Year:</strong> ${goal.year}</p>
            <p><strong>Department:</strong> ${goal.department}</p>
            <p><strong>Targets:</strong> ${goal.targets}</p>
            <p><strong>Total Budget:</strong> ${goal.total_budget}</p>
            <p><strong>Initiative:</strong> ${goal.initiative}</p>
        `;
        document.getElementById("goalDetails").innerHTML = details;
    }
});
    </script>

</body>
</html>

<?php
$conn->close();
?>
