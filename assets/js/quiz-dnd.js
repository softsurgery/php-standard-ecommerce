// Drag & drop handling with extra fields (choices / slider)
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
    // Update input/select/textarea/label
    row.querySelectorAll("input, select, textarea, label").forEach((el) => {
      if (el.name)
        el.name = el.name.replace(/questions\[\d+\]/g, `questions[${idx}]`);
      if (el.id)
        el.id = el.id.replace(/questions\[\d+\]/g, `questions[${idx}]`);
      if (el.tagName.toLowerCase() === "label" && el.htmlFor) {
        el.htmlFor = el.htmlFor.replace(
          /questions\[\d+\]/g,
          `questions[${idx}]`
        );
      }
    });

    // Reindex choices
    const choices = row.querySelectorAll(".choice");
    choices.forEach((choice, ci) => {
      choice.querySelectorAll("input").forEach((inp) => {
        inp.name = inp.name.replace(/choices\[\d+\]/g, `choices[${ci}]`);
      });
    });
  });
}

// Drag & drop for a row
function setupDragForRow(row) {
  row.addEventListener("dragstart", (e) => {
    dragged = row;
    row.classList.add("dragging");
    e.dataTransfer.setData("text/plain", "dragging");
    e.dataTransfer.effectAllowed = "move";
  });

  row.addEventListener("dragend", () => {
    if (dragged) dragged.classList.remove("dragging");
    dragged = null;
    removeDropHover();
    reindexAll();
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

// Delete button for a row
function applyDeleteToRow(row) {
  const btn = row.querySelector(".delete-question");
  if (btn && !btn.dataset.deleteBound) {
    btn.addEventListener("click", () => {
      row.remove();
      reindexAll();
    });
    btn.dataset.deleteBound = "1";
  }
}

// Show/hide extra fields based on type
function updateExtraFields(row) {
  const typeSelect = row.querySelector("select[name*='[type]']");
  const type = typeSelect?.value || "";
  const extra = row.querySelector(".extra-fields");
  const choiceList = row.querySelector(".choice-list");
  const sliderFields = row.querySelector(".slider-fields");

  extra?.classList.add("hidden");
  choiceList?.classList.add("hidden");
  sliderFields?.classList.add("hidden");

  if (type === "CHECKBOX" || type === "RADIO") {
    extra?.classList.remove("hidden");
    choiceList?.classList.remove("hidden");
  } else if (type === "SLIDER") {
    extra?.classList.remove("hidden");
    sliderFields?.classList.remove("hidden");
  }
}

function addChoice(row) {
  const containerChoices = row.querySelector(".choices-container");
  if (!containerChoices) return;

  const qIndex = [...container.children].indexOf(row);
  const cIndex = containerChoices.children.length;

  // Get hidden template
  const template = document
    .getElementById("choice-template")
    .firstElementChild.cloneNode(true);

  // Replace placeholders with real indices
  template.innerHTML = template.innerHTML
    .replace(/__QINDEX__/g, qIndex)
    .replace(/__CINDEX__/g, cIndex);

  // Bind remove button
  template.querySelector(".remove-choice").addEventListener("click", () => {
    template.remove();
    reindexAll();
  });

  containerChoices.appendChild(template);
}

// Bind type change to update extra fields
function bindTypeChange(row) {
  const select = row.querySelector("select[name*='[type]']");
  if (select) {
    select.addEventListener("change", () => {
      updateExtraFields(row);
    });
  }
}

// Bind add-choice buttons
function bindAddChoice(row) {
  const addBtn = row.querySelector(".add-choice");
  if (addBtn && !addBtn.dataset.bound) {
    addBtn.addEventListener("click", () => addChoice(row));
    addBtn.dataset.bound = "1";
  }
}

// Apply all bindings to a row
function applyBindingsToRow(row) {
  setupDragForRow(row);
  applyDeleteToRow(row);
  bindTypeChange(row);
  bindAddChoice(row);

  // Setup remove buttons for existing choices
  row.querySelectorAll(".remove-choice").forEach((btn) => {
    if (!btn.dataset.bound) {
      btn.addEventListener("click", (e) => {
        e.target.closest(".choice").remove();
        reindexAll();
      });
      btn.dataset.bound = "1";
    }
  });

  updateExtraFields(row);
}

// Apply bindings to all existing rows
function applyBindingsToAllRows() {
  container
    .querySelectorAll(".question-row")
    .forEach((row) => applyBindingsToRow(row));
}

// Setup the quiz DND system
function setupQuizDND() {
  let questionCount = container.querySelectorAll(".question-row").length;
  const rawTemplate = templateEl.innerHTML;

  document.getElementById("add-question").addEventListener("click", () => {
    const html = buildQuestionHtml(questionCount, rawTemplate);
    const newRow = insertHtmlAsElement(html, container);
    newRow.classList.add("question-row");
    newRow.setAttribute("draggable", "true");
    applyBindingsToRow(newRow);
    reindexAll();
    questionCount++;
  });

  applyBindingsToAllRows();

  // Reindex before submit
  const form = document.getElementById("quiz-form");
  form?.addEventListener("submit", () => reindexAll());

  window.debugReindex = reindexAll;
}
