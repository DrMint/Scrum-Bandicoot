<?php

    function displayColumns($project, $sprint, $columns) {
        foreach ($columns as $columnIndex => $column) {
            echo '<div class="board-column">';
            echo '<div class="board-card">';
                echo '<h2>' . $column['title'] . '</h2>';
            echo '</div>';
            foreach ($column['tasks'] as $taskIndex => $task) {
                echo '<div class="board-card">';
                displayActions($project, $sprint, $columnIndex, $columns, $taskIndex);
                echo '<h3>' . $task['title'] . '</h3>';
                echo '<div class="board-assignees">';
                    foreach ($task['assignees'] as $assignee) {
                        echo '<p>' . $assignee . '</p>';
                    }
                echo '</div>';
                echo '</div>';
            }
            echo '<div class="board-card board-task-create">';
                echo '<a href="' . urlForCreate($project, $sprint, $columnIndex) . '">Create task</a>';
            echo '</div>';
            echo '</div>';
        }
    }

    function displayActions($project, $sprint, $columnIndex, $columns, $taskIndex=NULL) {
        echo '<div class="board-actions">';
            if ($columnIndex > 0) {
                echo '<a href="' . urlForLeft($project, $sprint, $columnIndex, $taskIndex) . '">◀</a>';
            } else {
                echo '<p></p>';
            }
            echo '<a href="' . urlForView($project, $sprint, $columnIndex, $taskIndex) . '">View</a>';
            echo '<a href="' . urlForEdit($project, $sprint, $columnIndex, $taskIndex) . '">Edit</a>';
            echo '<a href="' . urlForDelete($project, $sprint, $columnIndex, $taskIndex) . '">Delete</a>';
            if ($columnIndex < count($columns) - 1) {
                echo '<a href="' . urlForRight($project, $sprint, $columnIndex, $taskIndex) . '">▶</a>';
            } else {
                echo '<p></p>';
            }
        echo '</div>';
    }

    function displayFormEdit($project, $sprint, $columnIndex, $taskIndex, $task, $members) {
        echo '<div class="popup-form">';
            echo '<a href="' . '?project=' . $project . "&sprint=" . $sprint . '"><div class="background"></div></a>';
            echo '<form action="/projects/board' . urlForEdit($project, $sprint, $columnIndex, $taskIndex) . '" method="post">';
                echo '<h2>Edit a task</h2>';
                echo '<input class="title" type="text" name="title" placeholder="Task\'s title" value="' . $task['title'] . '">';
                echo '<textarea rows="5" cols="60" name="description" placeholder="Task\'s description">' . $task['description'] .'</textarea>';
                echo '<select multiple name="assignees[]">';
                    foreach ($members as $member) {
                        if (in_array($member, $task['assignees'])) {
                            echo '<option value="' . $member . '" selected>' . $member . '</option>';
                        } else {
                            echo '<option value="' . $member . '">' . $member . '</option>';
                        }
                    }
                echo '</select>';
                echo '<button type="submit" class="button outline" name="submitButton" value="Submit">Confirm</button>';
            echo '</form>';
        echo '</div>';
    }

    function displayFormView($project, $sprint, $task) {
        echo '<div class="popup-form">';
            echo '<a href="' . '?project=' . $project . "&sprint=" . $sprint . '"><div class="background"></div></a>';
            echo '<form>';
            echo '<div class="board-card">';
            echo '<h3>' . $task['title'] . '</h3>';
            echo '<p>' . $task['description'] . '</p>';
            foreach ($task['assignees'] as $assignee) {
                echo '<div class="board-assignees">';
                    echo '<p>' . $assignee . '</p>';
                echo '</div>';
            }
            echo '</div>';
            echo '</form>';
        echo '</div>';
    }

    function displayFormCreation($project, $sprint, $columnIndex) {
        echo '<div class="popup-form">';
            echo '<a href="' . '?project=' . $project . "&sprint=" . $sprint . '"><div class="background"></div></a>';
            echo '<form action="/projects/board' . urlForCreate($project, $sprint, $columnIndex) . '" method="post">';
                echo '<h2>Create new task</h2>';
                echo '<input class="title" type="text" name="title" placeholder="Title of the new task">';
                echo '<button type="submit" class="button outline"  name="submitButton" value="Submit">Create task</button>';
            echo '</form>';
        echo '</div>';
    }


    function urlForCreate($project, $sprint, $columnIndex) {
        return '?project=' . $project . '&sprint=' . $sprint . '&action=create&column=' . $columnIndex;
    }

    function urlForLeft($project, $sprint, $columnIndex, $taskIndex=NULL) {
        $result = '?project=' . $project . '&sprint=' . $sprint . '&action=left&column=' . $columnIndex;
        if (!is_null($taskIndex)) $result .= '&task=' . $taskIndex;
        return $result;
    }

    function urlForRight($project, $sprint, $columnIndex, $taskIndex=NULL) {
        $result = '?project=' . $project . '&sprint=' . $sprint . '&action=right&column=' . $columnIndex;
        if (!is_null($taskIndex)) $result .= '&task=' . $taskIndex;
        return $result;
    }

    function urlForView($project, $sprint, $columnIndex, $taskIndex) {
        $result = '?project=' . $project . '&sprint=' . $sprint . '&action=view&column=' . $columnIndex . '&task=' . $taskIndex;
        return $result;
    }

    function urlForEdit($project, $sprint, $columnIndex, $taskIndex=NULL) {
        $result = '?project=' . $project . '&sprint=' . $sprint . '&action=edit&column=' . $columnIndex;
        if (!is_null($taskIndex)) $result .= '&task=' . $taskIndex;
        return $result;
    }

    function urlForDelete($project, $sprint, $columnIndex, $taskIndex=NULL) {
        $result = '?project=' . $project . '&sprint=' . $sprint . '&action=delete&column=' . $columnIndex;
        if (!is_null($taskIndex)) $result .= '&task=' . $taskIndex;
        return $result;
    }
  
?>