// Inisialisasi DataTable untuk kedua tabel
let dataTable1 = new simpleDatatables.DataTable(document.getElementById("table1"));
let dataTable2 = new simpleDatatables.DataTable(document.getElementById("table2"));
let dataTable3 = new simpleDatatables.DataTable(document.getElementById("table3"));

// Fungsi untuk menyesuaikan elemen "per page dropdown"
function adaptPageDropdown(tableWrapper) {
  const selector = tableWrapper.querySelector(".dataTable-selector");
  selector.parentNode.parentNode.insertBefore(selector, selector.parentNode);
  selector.classList.add("form-select");
}

// Fungsi untuk menyesuaikan elemen-elemen pagination
function adaptPagination(tableWrapper) {
  const paginations = tableWrapper.querySelectorAll("ul.dataTable-pagination-list");

  for (const pagination of paginations) {
    pagination.classList.add(...["pagination", "pagination-primary"]);
  }

  const paginationLis = tableWrapper.querySelectorAll("ul.dataTable-pagination-list li");

  for (const paginationLi of paginationLis) {
    paginationLi.classList.add("page-item");
  }

  const paginationLinks = tableWrapper.querySelectorAll("ul.dataTable-pagination-list li a");

  for (const paginationLink of paginationLinks) {
    paginationLink.classList.add("page-link");
  }
}

// Fungsi untuk menyegarkan pagination setelah perubahan
const refreshPagination = () => {
  adaptPagination(dataTable1.wrapper);
  adaptPagination(dataTable2.wrapper);
  adaptPagination(dataTable3.wrapper);
};

// Patch "per page dropdown" dan pagination setelah tabel diinisialisasi
dataTable1.on("datatable.init", () => {
  adaptPageDropdown(dataTable1.wrapper);
  refreshPagination();
});
dataTable1.on("datatable.update", refreshPagination);
dataTable1.on("datatable.sort", refreshPagination);
dataTable1.on("datatable.page", () => {
  adaptPagination(dataTable1.wrapper);
});

dataTable2.on("datatable.init", () => {
  adaptPageDropdown(dataTable2.wrapper);
  refreshPagination();
});
dataTable2.on("datatable.update", refreshPagination);
dataTable2.on("datatable.sort", refreshPagination);
dataTable2.on("datatable.page", () => {
  adaptPagination(dataTable2.wrapper);
});

dataTable3.on("datatable.init", () => {
    adaptPageDropdown(dataTable3.wrapper);
    refreshPagination();
  });
  dataTable3.on("datatable.update", refreshPagination);
  dataTable3.on("datatable.sort", refreshPagination);
  dataTable3.on("datatable.page", () => {
    adaptPagination(dataTable3.wrapper);
  });
