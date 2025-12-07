// Drag & drop handling
let dragged = null;
const container = document.getElementById("questions-container");
const templateEl = document.getElementById("question-template");

// Utility: replace all occurrences of __INDEX__ with index
function buildQuestionHtml(index, rawTemplate) {
  return rawTemplate.replace(/__INDEX__/g, index);
}

// Insert node from HTML string and return the inserted element
function insertHtmlAsElement(html, parent) {
  const temp = document.createElement("div");
  temp.innerHTML = html.trim();
  const el = temp.firstElementChild;
  parent.appendChild(el);
  return el;
}

// Update name/id/for indices for all rows to reflect DOM order
function reindexAll() {
  const rows = container.querySelectorAll(".question-row");
  rows.forEach((row, idx) => {
    // For each input/select/textarea inside the row, replace index in name & id
    row.querySelectorAll("input, select, textarea, label").forEach((el) => {
      // Update name attribute (e.g., questions[3][label])
      if (el.name) {
        el.name = el.name.replace(/questions\[\d+\]/g, `questions[${idx}]`);
      }
      // Update id attribute
      if (el.id) {
        el.id = el.id.replace(/questions\[\d+\]/g, `questions[${idx}]`);
      }
      // Update label 'for' attributes
      if (el.tagName.toLowerCase() === "label" && el.htmlFor) {
        el.htmlFor = el.htmlFor.replace(
          /questions\[\d+\]/g,
          `questions[${idx}]`
        );
      }
    });
  });

  // keep the questionCount in sync with current number of rows
  questionCount = container.querySelectorAll(".question-row").length;
}

function setupDragForRow(row) {
  row.addEventListener("dragstart", (e) => {
    dragged = row;
    row.classList.add("dragging");
    // small data to enable drag in some browsers
    e.dataTransfer.setData("text/plain", "dragging");
    e.dataTransfer.effectAllowed = "move";
  });

  row.addEventListener("dragend", () => {
    if (dragged) dragged.classList.remove("dragging");
    dragged = null;
    removeDropHover();
    reindexAll(); // ensure indexes are correct after drop
  });

  row.addEventListener("dragover", (e) => {
    e.preventDefault();
    if (!dragged || dragged === row) return;
    removeDropHover();
    row.classList.add("drop-hover");
  });

  row.addEventListener("dragleave", () => {
    row.classList.remove("drop-hover");
  });

  row.addEventListener("drop", (e) => {
    e.preventDefault();
    if (!dragged || dragged === row) return;

    // Insert dragged before the drop target
    container.insertBefore(dragged, row);
    removeDropHover();
    reindexAll();
  });
}

function removeDropHover() {
  container
    .querySelectorAll(".drop-hover")
    .forEach((el) => el.classList.remove("drop-hover"));
}

function applyDeleteToAll() {
  const buttons = container.querySelectorAll(".delete-question");
  buttons.forEach((btn) => {
    if (!btn.dataset.deleteBound) {
      btn.addEventListener("click", () => {
        btn.closest(".question-row").remove();
        reindexAll();
      });
      btn.dataset.deleteBound = "1";
    }
  });
}

// Apply drag handlers to all rows (existing or newly added)
function applyDragToAll() {
  const rows = container.querySelectorAll(".question-row");
  rows.forEach((r) => {
    // Avoid binding multiple times — check a flag
    if (!r.dataset.dragBound) {
      setupDragForRow(r);
      r.dataset.dragBound = "1";
    }
  });
}

function setupQuizDND() {
  let questionCount = container.querySelectorAll(".question-row").length;

  // Read raw HTML template (must be innerHTML so placeholders remain)
  const rawTemplate = templateEl.innerHTML;

  // Add question handler
  document.getElementById("add-question").addEventListener("click", () => {
    const html = buildQuestionHtml(questionCount, rawTemplate);
    const newEl = insertHtmlAsElement(html, container);

    // Make sure newly inserted element has the expected class and draggable attribute
    // (render helpers already set them, but keep safe)
    newEl.classList.add("question-row");
    newEl.setAttribute("draggable", "true");

    // Bind drag events for the new row
    applyDragToAll();
    applyDeleteToAll();

    // Reindex names/ids so the new row gets proper index
    reindexAll();
  });

  // Initial setup
  applyDragToAll();
  reindexAll();

  // Optional: when form is submitted, ensure indexes are consistent
  const form = document.getElementById("quiz-form");
  form.addEventListener("submit", () => {
    reindexAll();
    // you can add validation here
  });

  // Expose a debug function in console (optional)
  window.debugReindex = reindexAll;
}
