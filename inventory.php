<?php
// Tell the server that you will be tracking session variables
session_start(); // starts a new session or resumes the existing session based on a session identifier passed via a GET or POST request, or passed via a cookie
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset = "utf-8"> <!-- tells the browser to use UTF-8 encoding -->
    <meta name="viewport" contents="width=device-width; initial-scale=1"> <!-- sets the viewport to the width of the device and sets the initial zoom level to 1 -->
    <!-- inventory.php - For keeping track of inventory items
    Wyatt Fredrickson
    Written: 11/1/2024
    Revised: N/A
    -->
    <title>My Inventory</title>
    <style>
        fieldset#fieldsetAdd { /* Styles for the fieldset add */
            border: #008000 solid 1px; /* forest green */
            border-radius: 3px; /* rounded corners */
        }
        fieldset#fieldsetAdd legend { /* Styles for the fieldset legend */
            color: #008000; /* forest green */
        }
        fieldset#fieldsetAdd input[type="submit"] { /* Styles for the submit button */
            color: #000080; /* dark blue */
            background: #cdffd8; /* light green */
            margin: .25em 0 0 0; /* Sets the margin to .25em on the top */
            height: 4em; /* Set the height to 4em */
            display: block; /* block level element */
            border-radius: 5px; /* rounded corners */
        }
        fieldset#fieldsetDelete { /* Styles for the fieldset delete */
            border: #800000 solid 1px; /* dark red */
            border-radius: 3px; /* rounded corners */
        }
        fieldset#fieldsetDelete legend { /* Styles for the fieldset legend */
            color: #800000; /* sets the color of the legend to dark red */
        }
        fieldset#fieldsetDelete input[type="submit"] { /* Sets the style of the submit button */
            color: #800000; /* dark red */
            background: #ffe0e3; /* light red */
            border: #800000 solid 1px; /* sets the border color to dark red */
            height: 4em; /* sets the height of the button to 4em */
            display: block; /* block level element */
            border-radius: 5px; /* rounded corners */
            float: left; /* aligns the button to the left */
        }
        table { /* Styles the table */
            width: 70%; /* sets the width of the table to 70% of the screen */
            margin: .5em; /* sets the margin of the table */
            border-collapse: collapse; /* collapses the borders of the table */
        }

        table, td { /* Styles the table and table data cells */
            border: 1px solid #008000; /* forest green */
            padding: .25em; /* padding of .25em */
        }

        th { /* Styles the table headers */
            background: #008000; /* forest green */
            color: white; /* white text */
            border: 2px white solid; /* white border */
            padding: .5em; /* padding of .5em */
        }

        tr:nth-child(even) { /* styles the even rows */
            background: #f0fcf3; /* very light green */
        }

        tr:nth-child(odd) { /* styles the odd rows */
            background: #eee; /* very light gray */
        }

        img { /* styles the image */
            float: right; /* aligns the image to the right */
            width: 40%; /* sets the width of the image to 40% of the screen */
            margin: -4em 0 1em 0; /* sets the margin of the image */
        }
    </style>
</head>
<body>
    <?php
    // The filename of the currently executing script to be used as the action=" " attribute of the form element
    $self = htmlspecialchars($_SERVER['PHP_SELF']); // gets the filename of the currently executing script and stores it in the variable $self
    // htmlspecialchars() function converts special characters to HTML entities

    // Check to see if the page has been viewed already
    // The hidSubmitFlag will not exist if this is the first time
    if(array_key_exists('hidSubmitFlag', $_POST)) { // checks to see if the submit flag variable exists in the POST array
        echo "<h2>Welcome back!</h2>"; // prints a message to the screen
        $submitFlag = $_POST['hidSubmitFlag']; // stores the value of the submit flag variable from the form
        //echo "DEBUG: hidSubmitFlag is: $submitFlag<br />"; // prints a debug message to the screen to show the value of the submit flag variable
        //echo "DEBUG: hidSubmitFlag is type of: " . gettype($submitFlag) . "<br />"; // prints a debug message to the screen to show the data type of the submit flag variable

        // Get the serialized array that was stored in the session variable
        $invenArray = unserialize(urldecode($_SESSION['serializedArray'])); // retrieves the serialized array from the SESSION variable, decodes it, and unserializes it
        switch($submitFlag) {
            case "01": addRecord(); // calls the addRecord() function if the submit flag is 01
            break;
            case "99": deleteRecord(); // calls the deleteRecord() function if the submit flag is 99
            break;
            default: displayInventory($invenArray); // calls the displayInventory() function if the submit flag is anything else
            // More cases can be added here
        }
    }
    else {
            echo "<h2>Welcome to the Inventory Page</h2>";
            // First time visitor? If they are, create the inventory array
            // Create the inventory array
            $invenArray = array(); // creates an empty 2D array, which will be used to store the inventory data
            $invenArray[0][0] = "1111";
            $invenArray[0][1] = "Rose";
            $invenArray[0][2] = "1.95";
            $invenArray[0][3] = "100";

            $invenArray[1][0] = "2222";
            $invenArray[1][1] = "Dandelion Tree";
            $invenArray[1][2] = "2.95";
            $invenArray[1][3] = "200";

            $invenArray[2][0] = "3333";
            $invenArray[2][1] = "Crabgrass Bush";
            $invenArray[2][2] = "3.95";
            $invenArray[2][3] = "300";

            // Save the array inventory as a serialized session variable
            $_SESSION['serializedArray'] = urlencode(serialize($invenArray)); // $invenArray is serialized and then URL encoded and stored in the SESSION variable serializedArray
        }


    // Function to add a record
    function addRecord() {
        global $invenArray;
        // Add the new information into the global array $invenArray
        $invenArray[] = array($_POST['txtPartNo'], $_POST['txtDescr'], $_POST['txtPrice'], $_POST['txtQty']);
        // Save the updated array in its session variable
        sort($invenArray); // sorts the array
        // Save the array inventory as a serialized session variable
        $_SESSION['serializedArray'] = urlencode(serialize($invenArray)); // $invenArray is serialized and then URL encoded and stored in the SESSION variable serializedArray
    }
    // Function to delete a record
    function deleteRecord() {
        global $invenArray;
        global $deleteMe;
        // Get the selected index from the lstItem list box in the form
        $deleteMe = $_POST['lstItem']; // $deleteMe is assigned the value of the selected item from the list box in the form
        // Remove the selected index from the array
        unset($invenArray[$deleteMe]); // removes the selected item from the array
        // Save the updated array in its session variable
        $_SESSION['serializedArray'] = urlencode(serialize($invenArray)); // $invenArray is serialized and then URL encoded and stored in the SESSION variable serializedArray
        echo "<h2>Record deleted</h2>"; // prints a message to the screen to indicate that the record was deleted
    }
    // Function to display the inventory
    function displayInventory() {
        global $invenArray;
        echo "<table border='1'>";
        // Display the inventory
        echo "<tr>"; // creates a row
        echo "<th>Part No.</th>";
        echo "<th>Description</th>";
        echo "<th>Price</th>";
        echo "<th>Qty</th>";
        echo "</tr>";
        // Using a for each loop to walk through each record or (row) in the array
        foreach($invenArray as $record) { // walks through each record in the array
            echo "<tr>"; // creates a row
            foreach($record as $value) { // walks through each value in the record
                echo "<td>$value</td>"; // creates a data cell
            }
            echo "</tr>"; // closes the row
        }
        echo "</table>"; // closes the table
    }
    ?>

<img src="branch.jpg" alt="Tree branch with leaves"/>

<!-- This is the "header" beginning of the page -->
<h1>Plants You-nique</h1> <!-- creates a level 1 heading -->
    <p> <!-- creates a paragraph -->
    <h2>Here is our current inventory:<br /></h2> <!-- creates a level 2 heading -->
    <?php displayInventory(); ?> <!-- calls the displayInventory() function to display the inventory -->
    </p> <!-- closes the paragraph -->



<!-- A form to add a record -->
<form action="<?php echo $self; ?> "method="POST" name="frmAdd"> <!-- creates a form and sets the action to the current page and the method to POST -->

    <fieldset id="fieldsetAdd"> <!-- creates a fieldset with the id fieldsetAdd for styling -->
        <legend>Add an item</legend> <!-- creates a legend -->

        <label for="txtPartNo">Part Number:</label> <!-- A label for the part number field -->
        <input type="text" name="txtPartNo" id="txtPartNo" value="999" size="5"/>
        <br /><br /> <!-- creates a text input field for the part number -->

        <label for="txtDescr">Description:</label> <!-- A label for the description field -->
        <input type="text" name="txtDescr" id="txtDescr" value="Test Descr" />
        <br /><br /> <!-- creates a text input field for the description -->
    
        <label for="txtPrice">Price: $US</label> <!-- A label for the price field -->
        <input type="text" name="txtPrice" id="txtPrice" value="123.45" />
        <br /><br /> <!-- creates a text input field for the price -->

        <label for="txtQty">Quantity in stock:</label> <!-- A label for the quantity field -->
        <input type="text" name="txtQty" id="txtQty" value="123" size="5" />
        <br /><br /> <!-- creates a text input field for the quantity -->
        <!-- This field is used to determine if the page has been viewed already Code 01 = Add -->
        <input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='01' /> <!-- creates a hidden input field for the submit flag so the page knows it has been viewed. -->
        <input name="btnSubmit" type="submit" value="Add this information" /> <!-- creates a submit button -->
    </fieldset>
</form> <!-- closes the form -->
    


<!-- A form to delete a record -->
<form action="<?php echo $self; ?> "method="POST" name="frmDELETE"> <!-- creates a form and sets the action to the current page and the method to POST -->

    <fieldset id="fieldsetDelete"> <!-- creates a fieldset with the id fieldsetDelete for styling -->
    <legend>Select an item to delete</legend> <!-- creates a legend -->

    <select name="lstItem" size="1"> <!-- creates a list box -->
        <?php
            // Populate the list box using data from the array
            foreach($invenArray as $index => $lstRecord) {
                // Make the value the index and the text displayed the description from the array
                // The index will be used by deleteRecord()
                echo "<option value='" . $index . "'>" . $lstRecord[1] . "</option>\n";
            }
        ?>
        </select> <!-- closes the list box -->
        <!-- This field is used to determine if the page has been viewed already Code 99 = Delete -->
        <input type='hidden' name='hidSubmitFlag' id='hidSubmitFlag' value='99' /><br /><br /> <!-- creates a hidden input field for the submit flag so the page knows it has been viewed. -->
        <input name="btnSubmit" type="submit" value="Delete this selection" /> <!-- creates a submit button -->
    </fieldset> <!-- closes the fieldset -->
</form> <!-- closes the form -->
<p style="font-size:14px;font-weight:200;">
Photo by anonymous on stock.adobe <a href="https://stock.adobe.com/search?k=tree+branch+with+leaves" target="_blank">https://stock.adobe.com/search?k=tree+branch+with+leaves</a>
</p>







</body> <!-- closes the body -->
</html> <!-- closes the html -->