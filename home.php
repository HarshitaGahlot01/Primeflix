<?php
session_start();

// Redirect to login page if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>PrimeFlix - Home</title>
  <style>
 /* Your CSS from before with spacing fixes */
* {
  box-sizing: border-box;
}
body {
  margin: 0;
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background-color: #121212;
  color: #fff;
  line-height: 1.6;
}
.navbar {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  background-color: #141414;
  padding: 10px 20px;
  border-bottom: 1px solid #222;
}
.main-title {
  color: #e50914;
  font-size: 28px;
  font-weight: 700;
  margin: 0;
  flex: 1 1 150px;
}

/* New container wrapping search + dropdowns */
.controls-container {
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  gap: 10px; /* space between controls */
  flex: 2 1 600px; /* adjust width as needed */
}

.search-container {
  display: flex;
  flex: 1 1 300px;
  max-width: 100%;
}
.search-container input {
  flex-grow: 1;
  padding: 8px 12px;
  border: none;
  border-radius: 4px 0 0 4px;
  font-size: 16px;
  outline: none;
  min-width: 180px;
}
.search-container button {
  background-color: #e50914;
  border: none;
  color: white;
  padding: 8px 12px;
  cursor: pointer;
  border-radius: 0 4px 4px 0;
  font-size: 18px;
  transition: background-color 0.3s;
  flex-shrink: 0;
}
.search-container button:hover {
  background-color: #b0060f;
}
.btn {
  background-color: #e50914;
  color: white;
  padding: 8px 14px;
  text-decoration: none;
  border: none;
  border-radius: 4px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s;
  font-size: 14px;
  flex-shrink: 0;
}
.btn:hover {
  background-color: #b0060f;
}
select.btn {
  background-color: #e50914;
  color: white;
  border: none;
  padding: 8px 14px;
  border-radius: 4px;
  font-weight: 600;
  cursor: pointer;
  font-size: 14px;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  background-image: url("data:image/svg+xml;charset=US-ASCII,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='white'><polygon points='0,0 20,0 10,10'/></svg>");
  background-repeat: no-repeat;
  background-position: right 10px center;
  background-size: 10px;
}
main {
  padding: 20px 30px;
  max-width: 1200px;
  margin: auto;
}
section {
  margin-bottom: 40px;
}
section h2 {
  border-left: 6px solid #e50914;
  padding-left: 10px;
  font-weight: 700;
  font-size: 24px;
  margin-bottom: 20px;
}
.movie-row {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
}
.movie-card {
  background-color: #222;
  border-radius: 8px;
  width: 150px;
  cursor: pointer;
  transition: transform 0.3s;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.7);
  text-align: center;
  position: relative; /* added for favorite icon positioning */
}
.movie-card:hover {
  transform: scale(1.05);
  box-shadow: 0 6px 12px rgba(229, 9, 20, 0.7);
}
.movie-card img {
  width: 100%;
  display: block;
  border-bottom: 1px solid #333;
  height: 225px;
  object-fit: cover;
}
.movie-card p {
  margin: 10px;
  font-size: 14px;
  font-weight: 600;
  color: #eee;
}
@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    align-items: flex-start;
  }
  .controls-container {
    flex-wrap: wrap;
    gap: 8px;
    width: 100%;
    margin-top: 10px;
  }
  .search-container {
    flex: 1 1 100%;
    max-width: 100%;
  }
  .movie-card {
    width: 45%;
  }
}
@media (max-width: 480px) {
  .movie-card {
    width: 100%;
  }
}

/* Favorite heart icon */
.favorite-icon {
  position: absolute;
  top: 8px;
  right: 8px;
  color: #ccc;
  font-size: 22px;
  cursor: pointer;
  user-select: none;
  transition: color 0.3s;
  text-shadow: 0 0 3px rgba(0,0,0,0.7);
  z-index: 10;
}

.favorite-icon.active {
  color: #e50914;
}
</style>

</head>
<body>
  <p style="color: #eee; margin-left: 15px;">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
  <header class="navbar">
    <h1 class="main-title">PrimeFlix</h1>

    <div class="controls-container">
      <div class="search-container">
        <input type="text" id="search-input" placeholder="Search movies..." />
        <button onclick="applyFilters()" class="btn">🔍</button>
      </div>

      <select class="btn" id="movies-select" onchange="handleMovieSelect()">
        <option value="all" selected>🎬 Movies</option>
        <option value="upcoming">📅 Upcoming Movies</option>
        <option value="top">⭐ Top Movies</option>
      </select>

      <a href="#" id="favorites-btn" class="btn" onclick="toggleShowFavorites(event)">Favorites</a>


      <select class="btn" id="category-select" onchange="filterMovies()">
        <option value="">🎬 Categories</option>
        <option value="Action">Action</option>
        <option value="Comedy">Comedy</option>
        <option value="Drama">Drama</option>
        <option value="Horror">Horror</option>
        <option value="Sci-Fi">Sci-Fi</option>
        <option value="Romance">Romance</option>
        <option value="Thriller">Thriller</option>
      </select>

      <select class="btn" id="mood-select" onchange="filterMovies()">
        <option value="">🎭 Mood</option>
        <option value="happy">😊 Happy</option>
        <option value="sad">😢 Sad</option>
        <option value="romantic">💖 Romantic</option>
        <option value="thrilling">😱 Thrilling</option>
        <option value="adventurous">🧭 Adventurous</option>
        <option value="inspirational">🌟 Inspirational</option>
      </select>

      <select class="btn" id="language-select" onchange="filterMovies()">
        <option value="">🌐 Language</option>
        <option value="en">English</option>
        <option value="hi">Hindi</option>
        
      </select>
    </div>
  </header>

  <main>
    <section>
      <h2>Filtered Movies</h2>
      <div id="filtered-movies" class="movie-row"></div>
</section>

  </main>

  <script src="moviesData.js"></script>
  <script src="main.js"></script>
</body>
</html>
