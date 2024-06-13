<?php

require_once(__DIR__ . '/../../database/Database.php');

use database\Database;

/**
 * Generates a random title for a task.
 *
 * @return string A random task title.
 */
function getRandomTitle(): string
{
    $titles = [
        'Buy groceries',
        'Write blog post',
        'Go for a run',
        'Complete homework',
        'Read a book',
        'Clean the house',
        'Plan vacation',
        'Finish project report',
        'Call a friend',
        'Attend meeting'
    ];

    return $titles[array_rand($titles)];
}

/**
 * Generates a random description for a task.
 *
 * @return string A random task description.
 */
function getRandomDescription(): string
{
    $descriptions = [
        'Remember to buy milk, eggs, and bread.',
        'Write about the latest trends in technology.',
        'Run 5 kilometers in the park.',
        'Finish math and science assignments.',
        'Read the new mystery novel.',
        'Vacuum, dust, and organize the living room.',
        'Plan itinerary and book hotels.',
        'Finalize the report and submit it to the manager.',
        'Catch up with an old friend over the phone.',
        'Prepare for the team meeting at work.'
    ];

    return $descriptions[array_rand($descriptions)];
}

/**
 * Generates a random execution date between today and 30 days from now.
 *
 * @return string A random execution date in 'Y-m-d' format.
 */
function getRandomDate(): string
{
    return date('Y-m-d', strtotime('+' . mt_rand(1, 30) . ' days'));
}

try {
    $conn = Database::getConnection();

    for ($i = 0; $i < 10; $i++) {
        $title = getRandomTitle();
        $description = getRandomDescription();
        $execution_date = getRandomDate();

        $stmt = $conn->prepare("INSERT INTO tasks (title, description, execution_date) VALUES (:title, :description, :execution_date)");
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':execution_date', $execution_date);
        $stmt->execute();
    }

    echo "10 random tasks have been added to the database. \n";
} catch (PDOException $e) {
    echo "Error inserting tasks: " . $e->getMessage();
}
