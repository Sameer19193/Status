<?php
include 'crud.php';  // Include the CRUD module

// Ensure the table exists
createTableIfNotExists();

// Handle form submissions
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ADD or UPDATE a website
    if (isset($_POST['url']) && isset($_POST['company_name'])) {
        $websiteId = $_POST['website_id'] ?? null;
        $url = $_POST['url'];
        $companyName = $_POST['company_name'];

        if ($websiteId) {
            // Update an existing URL and company name
            $rowsAffected = updateWebsite($websiteId, $url, $companyName);
            $message = "$rowsAffected rows updated.";
        } else {
            // Add a new URL and company name
            $websiteId = createWebsite($url, $companyName);
            $message = "Website added with ID: $websiteId";
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    // DELETE a website
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];
        $rowsAffected = deleteWebsite($deleteId);
        $message = "$rowsAffected rows deleted.";
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Fetch all websites
$websites = getWebsites();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website CRUD</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <style>
        /* Global Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            max-width: 90vw;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h3 {
            color: #333;
            margin-bottom: 20px;
        }

        .form-container {
            background-color: #f9fafb;
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border:2px solid darkgrey;
        }

        label {
            font-weight: 500;
            display: block;
            margin-bottom: 6px;
            color: #555;
        }

        input[type="text"], button {
            width: 98%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid grey;
            border-radius: 4px;
            font-size: 16px;
        }

        input[type="text"]:focus, button:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            background-color: #0056b3;
            color: white;
            cursor: pointer;
            border: none;
            transition: background-color 0.8s ease;
        }

        button:hover {
            background-color: #007bff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border:2px solid grey;
            border-radius: 4px;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid grey;
            font:black;
        }

        table th {
            background-color: lightgrey;
        }

        table tr:hover {
            background-color: #f9f9f9;
        }

        table td button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 4px;
            border: none;
            transition: background-color 0.3s ease;
        }

        table td button:hover {
            background-color: #218838;
        }

        table td form button {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
        }

        table td form button:hover {
            background-color: #c82333;
        }

        @media (max-width: 768px) {
            .container {
                width: 100%;
                margin: 20px;
            }

            table th, table td {
                font-size: 14px;
                padding: 8px;
            }

            input[type="text"], button {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Display Message -->
    <?php if (!empty($message)): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Form for Adding or Updating a Website -->
    <div class="form-container">
        <h3>Manage Website</h3>
        <form method="POST">
            <input type="hidden" name="website_id" id="website_id">
            <label for="company_name">Company Name:</label>
            <input type="text" name="company_name" id="company_name" required>
            <label for="url">Website URL:</label>
            <input type="text" name="url" id="url" required>
            <button type="submit" id="submit-btn">Add/Update Website</button>
        </form>
    </div>

    <!-- Table Displaying All Websites -->
    <h3>All Websites</h3>
    <?php if (!empty($websites)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Company Name</th>
                    <th>URL</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($websites as $website): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($website['id']); ?></td>
                        <td><?php echo htmlspecialchars($website['company_name']); ?></td>
                        <td><?php echo htmlspecialchars($website['url']); ?></td>
                        <td>
                            <button onclick="editWebsite('<?php echo $website['id']; ?>', '<?php echo htmlspecialchars($website['url']); ?>', '<?php echo htmlspecialchars($website['company_name']); ?>')">Edit</button>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $website['id']; ?>">
                                <button type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No websites found.</p>
    <?php endif; ?>
</div>

<script>
    function editWebsite(id, url, companyName) {
        document.getElementById('website_id').value = id;
        document.getElementById('url').value = url;
        document.getElementById('company_name').value = companyName;
        document.getElementById('submit-btn').textContent = "Update Website";
    }
</script>

</body>
</html>
