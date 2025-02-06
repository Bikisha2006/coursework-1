<?php
session_start();

// Initialize the task list if not set
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['task'])) {
    $task = trim($_POST['task']);
    if (!empty($task)) {
        $_SESSION['tasks'][] = $task;
    }
}

// Handle task deletion
if (isset($_POST['delete_task'])) {
    $taskIndex = $_POST['delete_task'];
    array_splice($_SESSION['tasks'], $taskIndex, 1);
    echo json_encode($_SESSION['tasks']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fun Interactive To-Do List</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Comic+Neue:wght@300;700&display=swap');
        body {
            font-family: 'Comic Neue', cursive;
            text-align: center;
            background: linear-gradient(135deg, #ff9a9e, #fad0c4);
            padding: 20px;
            color: white;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            color: black;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #ff6b6b;
            color: white;
            padding: 10px;
            margin: 5px 0;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }
        li:hover {
            background: #ff4757;
        }
        button {
            padding: 10px;
            margin-top: 10px;
            background: #1dd1a1;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background: #10ac84;
        }
        input[type="text"] {
            padding: 8px;
            width: 70%;
            border: 2px solid #ff6b6b;
            border-radius: 8px;
            font-family: 'Comic Neue', cursive;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🎉 Fun To-Do List 🎈</h2>
    <form method="POST">
        <input type="text" name="task" placeholder="Enter a fun task!" required>
        <button type="submit">Add Task 🎯</button>
    </form>

    <h3>Your Tasks:</h3>
    <ul id="taskList">
        <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
            <li data-index="<?php echo $index; ?>"> <?php echo htmlspecialchars($task); ?> </li>
        <?php endforeach; ?>
    </ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("taskList").addEventListener("click", function (event) {
            if (event.target.tagName === "LI") {
                let index = event.target.getAttribute("data-index");
                deleteTask(index);
            }
        });
    });

    function deleteTask(index) {
        fetch("index.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "delete_task=" + index
        })
        .then(response => response.json())
        .then(tasks => {
            let taskList = document.getElementById("taskList");
            taskList.innerHTML = "";
            tasks.forEach((task, i) => {
                let li = document.createElement("li");
                li.textContent = task;
                li.setAttribute("data-index", i);
                taskList.appendChild(li);
            });
        });
    }
</script>

</body>
</html>