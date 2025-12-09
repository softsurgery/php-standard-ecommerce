// Parse URL query parameters
const urlParams = new URLSearchParams(window.location.search);

// Check for success or error
if (urlParams.get("success") === "true") {
  const message = urlParams.get("message") || "Action completed successfully!";
  showToast(message, "success", "bottom-right");
} else if (urlParams.get("error") === "true") {
  const message = urlParams.get("message") || "Something went wrong.";
  showToast(message, "error", "bottom-right");
}

// Remove query parameters from URL without reloading
if (urlParams.has("success") || urlParams.has("error")) {
  const newUrl = window.location.pathname; // keeps same path, removes query
  window.history.replaceState({}, document.title, newUrl);
}
