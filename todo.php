<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            background-image: url('xampp/htdocs/Protech/refrigerator.jpg'); /* Add refrigerator background image */
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }
        .container {
            width: 90%;
            max-width: 700px;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"],
        input[type="date"] {
            width: calc(100% - 340px); /* Adjusted width to accommodate due date input */
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        li.completed {
            text-decoration: line-through;
            color: #888;
            background-color: #cfe2f3; /* Highlight completed tasks in blue */
        }
        button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #007bff;
            margin-left: 5px;
        }
        @media only screen and (max-width: 600px) {
            .container {
                width: 100%;
            }
            input[type="text"],
            input[type="date"] {
                width: calc(100% - 120px); /* Adjusted width for smaller screens */
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Todo List</h2>
    <input type="text" id="taskInput" placeholder="Enter task...">
    <input type="date" id="dueDateInput"> <!-- Added input for due date -->
    <button id="addTaskBtn">Add</button>
    <ul id="taskList"></ul>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const taskInput = document.getElementById("taskInput");
        const dueDateInput = document.getElementById("dueDateInput"); // Added due date input
        const addTaskBtn = document.getElementById("addTaskBtn");
        const taskList = document.getElementById("taskList");

        // Load tasks from local storage
        loadTasks();

        // Event listener for adding a new task
        addTaskBtn.addEventListener("click", function() {
            const taskText = taskInput.value.trim();
            const dueDate = dueDateInput.value; // Get the due date value
            if (taskText !== "") {
                const task = {
                    text: taskText,
                    completed: false,
                    dueDate: dueDate, // Add due date to the task object
                    timestamp: new Date().getTime()
                };
                addTask(task);
                saveTasks();
                taskInput.value = "";
                dueDateInput.value = ""; // Clear the due date input
            } else {
                alert("Please enter a task.");
            }
        });

        // Function to add a new task to the list
        function addTask(task) {
            const li = document.createElement("li");
            li.innerHTML = `
                <span class="task-text">${task.text}</span>
                <span class="task-due">Due: ${task.dueDate}</span>
                <button class="complete-btn">Complete</button>
                <button class="delete-btn">Delete</button>
                <button class="edit-btn">Edit</button>
            `;
            if (task.completed) {
                li.classList.add("completed");
            }
            taskList.appendChild(li);

            // Event listeners for complete, delete, and edit buttons
            const completeBtn = li.querySelector(".complete-btn");
            const deleteBtn = li.querySelector(".delete-btn");
            const editBtn = li.querySelector(".edit-btn");

            completeBtn.addEventListener("click", function() {
                li.classList.toggle("completed");
                task.completed = !task.completed;
                saveTasks();
            });

            deleteBtn.addEventListener("click", function() {
                li.remove();
                saveTasks();
            });

            editBtn.addEventListener("click", function() {
                const newText = prompt('Enter the new task:', task.text);
                if (newText !== null && newText.trim() !== '') {
                    task.text = newText.trim();
                    li.querySelector('.task-text').textContent = newText.trim();
                    saveTasks();
                }
            });
        }

        // Function to load tasks from local storage and sort by due date
        function loadTasks() {
            const tasks = JSON.parse(localStorage.getItem("tasks")) || [];
            tasks.sort((a, b) => new Date(a.dueDate) - new Date(b.dueDate)); // Sort by due date in descending order
            tasks.forEach(addTask);
        }

        // Function to save tasks to local storage
        function saveTasks() {
            const tasks = Array.from(taskList.children).map(function(li) {
                return {
                    text: li.querySelector(".task-text").textContent,
                    completed: li.classList.contains("completed"),
                    dueDate: li.querySelector(".task-due").textContent.split("Due: ")[1],
                    timestamp: parseInt(li.querySelector(".task-due").getAttribute("data-timestamp"))
                };
            });
            localStorage.setItem("tasks", JSON.stringify(tasks));
        }
    });

</script>

</body>
</html>
