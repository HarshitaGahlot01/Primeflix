// Define IDs arrays
const upcomingIds = [1, 2, 3, 50, 51, 52, 53, 54, 55, 56, 57];
const topMovieIds = [58, 59, 60, 61, 62, 63, 64, 65, 66, 67, 68, 69, 70, 71, 72];

// Global favorites set
const favorites = new Set();
let showFavoritesOnly = false;

// Keep global filtered movies list
let filtered = [...movies];

// Toggle favorite on heart click
function toggleFavorite(event, movieId) {
  event.stopPropagation(); // prevent bubbling if needed
  if (favorites.has(movieId)) {
    favorites.delete(movieId);
  } else {
    favorites.add(movieId);
  }
  applyFilters(); // re-filter and render movies after toggle
}

// Toggle favorites filter on/off when Favorites button clicked
function toggleShowFavorites(event) {
  event.preventDefault();
  showFavoritesOnly = !showFavoritesOnly;

  const btn = document.getElementById('favorites-btn');
  if (showFavoritesOnly) {
    btn.style.backgroundColor = '#b0060f'; // active red color
  } else {
    btn.style.backgroundColor = '#e50914'; // default color
  }

  applyFilters();
}

// Filter movies based on search, dropdowns and favorites flag
function applyFilters() {
  const searchText = document.getElementById("search-input").value.trim().toLowerCase();
  const category = document.getElementById("category-select").value.toLowerCase();
  const mood = document.getElementById("mood-select").value.toLowerCase();
  const language = document.getElementById("language-select").value.toLowerCase();
  const movieSelect = document.getElementById("movies-select").value; // all, upcoming, top

  filtered = movies.filter(m => {
    const matchesSearch = searchText ? m.title.toLowerCase().includes(searchText) : true;
    const matchesCategory = category ? m.category.toLowerCase() === category : true;
    const matchesMood = mood ? m.mood.toLowerCase() === mood : true;
    // Fixed language filtering line here:
    const matchesLanguage = (language && language !== "all") ? m.language.toLowerCase() === language : true;

    let matchesMovieSelect = true;
    if (movieSelect === 'upcoming') {
      matchesMovieSelect = upcomingIds.includes(m.id);
    } else if (movieSelect === 'top') {
      matchesMovieSelect = topMovieIds.includes(m.id);
    } else if (movieSelect === 'all') {
      matchesMovieSelect = true;
    }

    if (showFavoritesOnly) {
      return favorites.has(m.id) && matchesSearch && matchesCategory && matchesMood && matchesLanguage && matchesMovieSelect;
    } else {
      return matchesSearch && matchesCategory && matchesMood && matchesLanguage && matchesMovieSelect;
    }
  });

  renderMovies("filtered-movies", filtered);
}

// Render movies with heart icon clickable
function renderMovies(containerId, movieList) {
  const container = document.getElementById(containerId);
  container.innerHTML = "";
  if (movieList.length === 0) {
    container.innerHTML = "<p>No movies found.</p>";
    return;
  }

  movieList.forEach(movie => {
    const isFav = favorites.has(movie.id);

    const card = document.createElement("div");
    card.className = "movie-card";
    card.title = movie.title;

    card.innerHTML = `
      <img src="${movie.poster}" alt="${movie.title}" />
      <span class="favorite-icon ${isFav ? 'active' : ''}" onclick="toggleFavorite(event, ${movie.id})">&#10084;</span>
      <p>${movie.title}</p>
    `;

    container.appendChild(card);
  });
}

// When movies select dropdown changes
function handleMovieSelect() {
  document.getElementById("search-input").value = "";
  applyFilters();
}

// When other filters change
function filterMovies() {
  document.getElementById("search-input").value = "";
  applyFilters();
}

// Init page
function init() {
  document.getElementById("movies-select").value = "all";
  applyFilters();
}

window.onload = init;
