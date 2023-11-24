<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Assignment 6 RESTful CRUD</title>
    <link rel="stylesheet" href="songDataStyle.css">
    <script src="main.js"></script>
    <style>
        #content {
            font-size: 1rem;
            font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
        }

        .selected {
            background-color: #0096C7;
        }

        .container {
            margin: auto;
        }

        table {
            padding: 1.5rem;
        }

        table,
        td,
        th {
            border: solid thin black;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 0.5rem;
        }

        th {
            background-color: #006FB9;
            color: whitesmoke;
            font-weight: bold;
        }

        #buttonContainer {
            display: flex;
        }

        button {
            font-size: 1rem;
            border-radius: 0.3rem;
            margin-bottom: 0.5rem;
        }

        .btn-primary {
            background-color: #006FB9;
            color: #333;
        }

        .btn-primary:disabled {
            opacity: 0.5;
            background-color: #0096C7;
        }

        .hidden {
            display: none;
        }

        #addUpdateContainer {
            border: solid thin black;
            padding: 1rem;
            margin: 1rem;
        }

        .inputContainer {
            display: flex;
        }
    </style>
</head>

<body>
    <div id="content" class="container">
        <h1>Song Database</h1>

        <div id="buttonContainer">
            <button id="btnAdd" class="btn-primary">Add Song</button>
            <button id="btnDelete" class="btn-primary" disabled>Delete Song</button>
            <button id="btnUpdate" class="btn-primary" disabled>Update Song</button>
        </div>

        <div id="addUpdateContainer" class="hidden">
            <div class="inputContainer">
                <div class="inputLabel">Song ID:</div>
                <div class="inputField">
                    <input id="songID" name="songID" type="number" min="100" max="999" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Genre:</div>
                <div class="inputField">
                    <select id="songGenreID" name="songGenreID"><!-- JavaScript --></select>
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Song Title:</div>
                <div class="inputField">
                    <input id="songTitle" name="songTitle" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Artist:</div>
                <div class="inputField">
                    <input id="artist" name="artist" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Album:</div>
                <div class="inputField">
                    <input id="album" name="album" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">Length:</div>
                <div class="inputField">
                    <input id="length" name="length" />
                </div>
            </div>
            <div class="inputContainer">
                <div class="inputLabel">&nbsp;</div>
                <div class="inputField">
                    <button id="btnDone" class="btn-primary">Done</button>
                    <button id="btnCancel" class="btn-primary">Cancel</button>
                </div>
            </div>
        </div>

        <div id="songDataTable">
            <!-- JavaScript -->
        </div>
    </div>
</body>

</html>