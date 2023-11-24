let addOrUpdate;

window.onload = function () {

    document.querySelector("#btnAdd").addEventListener("click", addSong);
    document.querySelector("#btnDelete").addEventListener("click", deleteSong);
    document.querySelector("#btnUpdate").addEventListener("click", updateSong);
    document.querySelector("#btnDone").addEventListener("click", processForm);
    document.querySelector("#btnCancel").addEventListener("click", hideUpdateContainer);
    document.querySelector("#songDataTable").addEventListener("click", handleRowClick);

    initUpdatePanel();
    hideUpdateContainer();
    getAllSongs();
};

function initUpdatePanel() {
    let url = "songRecords/genres";
    let method = "GET";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            let resp = JSON.parse(xhr.responseText);
            if (resp.data !== null) {
                buildComboBox(resp.data);
            } else {
                alert(resp.error + " status code: " + xhr.status);
            }
        }
    };
    xhr.open(method, url, true);
    xhr.send();
}

function buildComboBox(text) {
    let arr = JSON.parse(text);
    let html = "";
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        html +=
            "<option value='" + 
            row.songGenreID + 
            "'>" +
            row.songGenreDescription + 
            "</option>";
    }
    let selectElement = document.querySelector("select#songGenreID");
    selectElement.innerHTML = html;
}

// make AJAX call to get JSON data
function getAllSongs() {
    let url = "songRecords/songs"
    let method = "GET";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            let resp = JSON.parse(xhr.responseText);
            if (resp.data) {
                buildTable(resp.data);
                setDeleteUpdateButtonState(false);
            } else {
                alert(resp.error + "; status code: " + xhr.status);
            }
        }
    };
    xhr.open(method, url, true);
    xhr.send();
}

// text is a JSON string containing an array
function buildTable(text) {
    let arr = JSON.parse(text); // get JS Objects
    let html =
        "<table><tr><th>ID</th><th>Genre</th><th>Song Title</th><th>Artist</th><th>Album</th><th>Length</th></tr>";
    for (let i = 0; i < arr.length; i++) {
        let row = arr[i];
        html += "<tr>";
        html += "<td>" + row.songID + "</td>";
        html += "<td>" + row.songGenreID + "</td>";
        html += "<td>" + row.songTitle + "</td>";
        html += "<td>" + row.artist + "</td>";
        html += "<td>" + row.album + "</td>";
        html += "<td>" + row.length + "</td>";
        html += "</tr>";
    }
    html += "</table>";
    let theTable = document.querySelector("#songDataTable");
    theTable.innerHTML = html;
}

function addSong() {
    addOrUpdate = "add";
    clearUpdateContainer();
    showUpdateContainer();
}

function updateSong() {
    addOrUpdate = "update";
    populateUpdateContainer();
    showUpdateContainer();
}

function deleteSong() {
    let row = document.querySelector(".selected");
    let id = Number(row.querySelectorAll("td")[0].innerHTML);
    // AJAX
    //let url = "api/deleteSong.php/?songID=" + id; // "?param=value"
    let url = "songRecords/songs/" + id;
    let method = "DELETE";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            let resp = JSON.parse(xhr.responseText);
            if (resp.data) {
                alert("Song deleted");
            } else {
                alert(resp.error + " status code: " + xhr.status);
            }
            getAllSongs();
        }
    };
    xhr.open(method, url, true);
    xhr.send();
}

function processForm() {
    // Get data from the form and build an object.
    let id = Number(document.querySelector("#songID").value);
    let genre = document.querySelector("#songGenreID").value;
    let title = document.querySelector("#songTitle").value;
    let artist = document.querySelector("#artist").value;
    let album = document.querySelector("#album").value;
    let length = document.querySelector("#length").value;

    let obj = {
        songID: id,
        songGenreID: genre,
        songTitle: title,
        artist: artist,
        album: album,
        length: length
    };

    // Make AJAX call to add or update the record in the database.
    //let url = addOrUpdate === "add" ? "api/addSong.php" : "api/updateSong.php";
    let url = "songRecords/songs/" + id;
    let method = addOrUpdate === "add" ? "POST" : "PUT";
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            let resp = JSON.parse(xhr.responseText);
            if (resp.data) {
                console.log(xhr.status);
                if (xhr.status === 200) {
                    alert("Song updated.");
                } else if (xhr.status === 201) {
                    alert("Song added.");
                }
            } else {
                alert(resp.error + " status code: " + xhr.status);
            }
            hideUpdateContainer();
            getAllSongs();
        }
    };
    xhr.open(method, url, true);
    xhr.send(JSON.stringify(obj));
}

function setIDFieldState(val) {
    let idInput = document.querySelector("#songIDInput");
    if (val) {
        idInput.removeAttribute("disabled");
    } else {
        idInput.setAttribute("disabled", "disabled");
    }
}

function hideUpdateContainer() {
    document.querySelector("#addUpdateContainer").classList.add("hidden");
}

function showUpdateContainer() {
    document.querySelector("#addUpdateContainer").classList.remove("hidden");
}

function clearUpdateContainer() {
    document.querySelector("#songID").value = "";
    document.querySelector("#songGenreID").value = "";
    document.querySelector("#songTitle").value = "";
    document.querySelector("#artist").value = "";
    document.querySelector("#album").value = "";
    document.querySelector("#length").value = "";
}

function populateUpdateContainer() {
    let selectedSong = document.querySelector(".selected");

    let songID = Number(selectedSong.querySelector("td:nth-child(1)").innerHTML);
    let songGenreID = selectedSong.querySelector("td:nth-child(2)").innerHTML;
    let songTitle = selectedSong.querySelector("td:nth-child(3)").innerHTML;
    let artist = selectedSong.querySelector("td:nth-child(4)").innerHTML;
    let album = selectedSong.querySelector("td:nth-child(5)").innerHTML;
    let length = selectedSong.querySelector("td:nth-child(6)").innerHTML;

    document.querySelector("#songID").value = songID;
    document.querySelector("#songGenreID").value = songGenreID;
    document.querySelector("#songTitle").value = songTitle;
    document.querySelector("#artist").value = artist;
    document.querySelector("#album").value = album;
    document.querySelector("#length").value = length;

}

function setDeleteUpdateButtonState(state) {
    if (state) {
        document.querySelector("#btnDelete").removeAttribute("disabled");
        document.querySelector("#btnUpdate").removeAttribute("disabled");
    } else {
        document.querySelector("#btnDelete").setAttribute("disabled", "disabled");
        document.querySelector("#btnUpdate").setAttribute("disabled", "disabled");
    }
}

function handleRowClick(evt) {
    clearSelections();
    evt.target.parentElement.classList.add("selected");

    setDeleteUpdateButtonState(true);
}

function clearSelections() {
    let trs = document.querySelectorAll("tr");
    for (let i = 0; i < trs.length; i++) {
        trs[i].classList.remove("selected");
    }
}