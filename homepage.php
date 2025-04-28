<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Music Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* --- Your Design Styles --- */
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            padding: 2rem;
            background-image: url('https://www.example.com/background-image.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .navbar {
            background-color: rgba(24, 24, 24, 0.8);
            border-radius: 16px;
            padding: 1rem 2rem;
            margin-bottom: 2rem;
        }
        .welcome {
            font-size: 2rem;
            margin-bottom: 1.5rem;
        }
        .btn-green {
            background-color: #1DB954;
            color: #fff;
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: 30px;
            border: none;
        }
        .btn-green:hover {
            background-color: #1ed760;
        }
        .card {
            background-color: rgba(24, 24, 24, 0.8);
            border: none;
            border-radius: 16px;
            color: #fff;
            padding: 2rem;
        }
        .music-player {
            margin-top: 1.5rem;
            padding: 1rem;
            border-radius: 16px;
            background-color: #222;
        }
        .music-player input {
            margin-bottom: 1rem;
            background-color: #333;
            color: #fff;
            border: none;
            padding: 0.5rem;
            border-radius: 5px;
        }
        .music-player button {
            background-color: #1DB954;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-size: 1rem;
        }
        .music-player button:hover {
            background-color: #1ed760;
        }
        .playlist {
            margin-bottom: 1.5rem;
            padding: 1rem;
            border-radius: 16px;
            background-color: #222;
            max-height: 200px;
            overflow-y: auto;
        }
        .playlist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            padding: 0.5rem;
            background-color: #333;
            border-radius: 5px;
        }
        .playlist-item:hover {
            background-color: #444;
        }
        .playlist-item button {
            background-color: #1DB954;
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 5px;
            font-size: 1rem;
            margin-right: 0.5rem;
        }
        .playlist-item button:hover {
            background-color: #1ed760;
        }
        .delete-btn {
            background-color: #e0245e;
        }
        .delete-btn:hover {
            background-color: #f50057;
        }
        .button-container {
            display: flex;
            gap: 10px;
        }
        .download-btn {
            background-color: #ffdd57;
            color: #333;
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            border: none;
            font-size: 1.1rem;
            margin-top: 2rem;
            display: block;
            text-align: center;
        }
        .download-btn:hover {
            background-color: #ffdb00;
        }
    </style>
</head>
<body>

<nav class="navbar d-flex justify-content-between align-items-center">
    <h4>Music Dashboard</h4>
    <a href="logout.php" class="btn btn-green">Logout</a>
</nav>

<div class="welcome">
    🎵 Welcome back and Enjoy the Music!!!
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Your Music Player</h5>

        <div class="playlist" id="playlist"></div>

        <div class="music-player">
            <input type="file" id="fileInput" accept="audio/*">
            <button id="downloadBtn">Download & Add to Playlist</button>
            <audio id="audioPlayer" preload="auto"></audio>
        </div>
    </div>
</div>

<a id="downloadFile" class="download-btn" href="#" download style="display: none;">Download Music</a>

<script>
const fileInput = document.getElementById("fileInput");
const downloadBtn = document.getElementById("downloadBtn");
const audioPlayer = document.getElementById("audioPlayer");
const playlistContainer = document.getElementById("playlist");
const downloadFile = document.getElementById("downloadFile");

// Initialize playlist (will reset every refresh)
let playlist = [];
let currentSongIndex = -1; // Initialize the current song index

// Add file to playlist
downloadBtn.addEventListener("click", () => {
    const file = fileInput.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const base64Data = e.target.result;
            playlist.push({ name: file.name, data: base64Data });
            displayPlaylist();
            fileInput.value = '';
        };
        reader.readAsDataURL(file); // Convert to Base64
    }
});

// Display playlist
function displayPlaylist() {
    playlistContainer.innerHTML = '';
    playlist.forEach((song, index) => {
        const songItem = document.createElement('div');
        songItem.classList.add('playlist-item');

        const songName = document.createElement('span');
        songName.textContent = song.name;

        const buttonContainer = document.createElement('div');
        buttonContainer.classList.add('button-container');

        const playButton = document.createElement('button');
        playButton.textContent = 'Play';
        playButton.onclick = () => playSong(index);

        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.classList.add('delete-btn');
        deleteButton.onclick = () => deleteSong(index);

        buttonContainer.appendChild(playButton);
        buttonContainer.appendChild(deleteButton);

        songItem.appendChild(songName);
        songItem.appendChild(buttonContainer);

        playlistContainer.appendChild(songItem);
    });
}

// Play a song
function playSong(index) {
    currentSongIndex = index; // Set the current song index
    const song = playlist[index];
    audioPlayer.src = song.data;
    audioPlayer.currentTime = 0;
    audioPlayer.play();
    downloadFile.style.display = 'block';
    downloadFile.href = song.data;
    downloadFile.download = song.name;
}

// Play next song when ended
audioPlayer.addEventListener('ended', () => {
    if (playlist.length > 0) {
        currentSongIndex = (currentSongIndex + 1) % playlist.length; // Move to the next song
        playSong(currentSongIndex); // Play the next song
    }
});

// Delete a song
function deleteSong(index) {
    playlist.splice(index, 1);
    displayPlaylist();
    audioPlayer.pause();  // Pause the player when a song is deleted
    audioPlayer.currentTime = 0;  // Reset the player to the start
    if (index === currentSongIndex) {
        // If the deleted song is currently playing, play the next one
        currentSongIndex = (currentSongIndex + 1) % playlist.length;
        if (playlist.length > 0) {
            playSong(currentSongIndex);
        }
    }
}
</script>

</body>
</html>
