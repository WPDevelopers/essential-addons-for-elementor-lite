class advancedDataTable {
  constructor() {
    // register hooks
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/eael-advanced-data-table.default",
      this.initFrontend.bind(this)
    );
  }

  // init frontend features
  initFrontend($scope, $) {
    let table = $scope[0].querySelector(".ea-advanced-data-table");
    let search = $scope[0].querySelector(".ea-advanced-data-table-search");
    let pagination = $scope[0].querySelector(
      ".ea-advanced-data-table-pagination"
    );
    let classCollection = {};

    if (!eael.isEditMode && table !== null) {
      // search
      this.initTableSearch(table, search, pagination);

      // sort
      this.initTableSort(table, pagination, classCollection);

      // paginated table
      this.initTablePagination(table, pagination, classCollection);

      // woocommerce
      this.initWooFeatures(table);

      let isEscapedHtmlString = function (str) {
        return /&[a-zA-Z]+;/.test(str);
      }, decodeEscapedHtmlString = function (str) {
        const textarea = document.createElement('textarea');
        textarea.innerHTML = str;
        return textarea.value;
      };

      if ($(table).hasClass('ea-advanced-data-table-static')) {
        $(table).find('th, td').each(function () {
          let text = $(this)[0].innerHTML;
          if (isEscapedHtmlString(text)) {
            text = decodeEscapedHtmlString(text);
            $(this).html(DOMPurify.sanitize(text));
          }
        });
      }
    }
  }

  // frontend - search
  initTableSearch(table, search, pagination) {
    if (search) {
      search.addEventListener("input", (e) => {
        let input = e.target.value.toLowerCase();
        let hasSort = table.classList.contains(
          "ea-advanced-data-table-sortable"
        );
        let offset =
          table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;

        if (table.rows.length > 1) {
          if (input.length > 0) {
            if (hasSort) {
              table.classList.add("ea-advanced-data-table-unsortable");
            }

            if (pagination && pagination.innerHTML.length > 0) {
              pagination.style.display = "none";
            }

            for (let i = offset; i < table.rows.length; i++) {
              let matchFound = false;

              if (table.rows[i].cells.length > 0) {
                for (let j = 0; j < table.rows[i].cells.length; j++) {
                  if (
                    table.rows[i].cells[j].textContent
                      .toLowerCase()
                      .indexOf(input) > -1
                  ) {
                    matchFound = true;
                    break;
                  }
                }
              }

              if (matchFound) {
                table.rows[i].style.display = "table-row";
              } else {
                table.rows[i].style.display = "none";
              }
            }
          } else {
            if (hasSort) {
              table.classList.remove("ea-advanced-data-table-unsortable");
            }

            if (pagination && pagination.innerHTML.length > 0) {
              pagination.style.display = "";

              let paginationType = pagination.classList.contains(
                "ea-advanced-data-table-pagination-button"
              )
                ? "button"
                : "select";
              let currentPage =
                paginationType == "button"
                  ? pagination.querySelector(
                      ".ea-adtp-current"
                    ).dataset.page
                  : pagination.querySelector("select").value;
              let startIndex =
                (currentPage - 1) * table.dataset.itemsPerPage + 1;
              let endIndex = currentPage * table.dataset.itemsPerPage;

              for (let i = 1; i <= table.rows.length - 1; i++) {
                if (i >= startIndex && i <= endIndex) {
                  table.rows[i].style.display = "table-row";
                } else {
                  table.rows[i].style.display = "none";
                }
              }
            } else {
              for (let i = 1; i <= table.rows.length - 1; i++) {
                table.rows[i].style.display = "table-row";
              }
            }
          }
        }
      });
    }
  }

  // frontend - sort
  initTableSort(table, pagination, classCollection) {
    if (table.classList.contains("ea-advanced-data-table-sortable")) {
      table.addEventListener("click", (e) => {
        let target = null;

        if (e.target.tagName.toLowerCase() === "th") {
          target = e.target;
        }

        if (e.target.parentNode.tagName.toLowerCase() === "th") {
          target = e.target.parentNode;
        }

        if (e.target.parentNode.parentNode.tagName.toLowerCase() === "th") {
          target = e.target.parentNode.parentNode;
        }

        if (target === null) {
          return;
        }

        let index = target.cellIndex;
        let currentPage = 1;
        let startIndex = 1;
        let endIndex = table.rows.length - 1;
        let sort = "";
        let classList = target.classList;
        let collection = [];
        let origTable = table.cloneNode(true);

        if (classList.contains("asc")) {
          target.classList.remove("asc");
          target.classList.add("desc");
          sort = "desc";
        } else if (classList.contains("desc")) {
          target.classList.remove("desc");
          target.classList.add("asc");
          sort = "asc";
        } else {
          target.classList.add("asc");
          sort = "asc";
        }

        if (pagination && pagination.innerHTML.length > 0) {
          let paginationType = pagination.classList.contains(
            "ea-advanced-data-table-pagination-button"
          )
            ? "button"
            : "select";

          currentPage =
            paginationType == "button"
              ? pagination.querySelector(
                  ".ea-adtp-current"
                ).dataset.page
              : pagination.querySelector("select").value;
          startIndex = (currentPage - 1) * table.dataset.itemsPerPage + 1;
          endIndex =
            endIndex - (currentPage - 1) * table.dataset.itemsPerPage >=
            table.dataset.itemsPerPage
              ? currentPage * table.dataset.itemsPerPage
              : endIndex;
        }

        // collect header class
        classCollection[currentPage] = [];

        table.querySelectorAll("th").forEach((el) => {
          if (el.cellIndex != index) {
            el.classList.remove("asc", "desc");
          }

          classCollection[currentPage].push(
            el.classList.contains("asc")
              ? "asc"
              : el.classList.contains("desc")
              ? "desc"
              : ""
          );
        });

        // collect table cells value
        for (let i = 1; i <= table.rows.length-1; i++) {
          let value;
          let cell = table.rows[i].cells[index];

          const data = cell.innerText;

          var regex = new RegExp(
            "([0-9]{4}[-./*](0[1-9]|1[0-2])[-./*]([0-2]{1}[0-9]{1}|3[0-1]{1})|([0-2]{1}[0-9]{1}|3[0-1]{1})[-./*](0[1-9]|1[0-2])[-./*][0-9]{4})"
          );

          if (data.match(regex)) {
            let dataString = data.split(/[\.\-\/\*]/), date = '';
            if (dataString[0].length == 4) {
              date = dataString[0] + "-" + dataString[1] + "-" + dataString[2];
            } else {
              date = dataString[2] + "-" + dataString[1] + "-" + dataString[0];
            }
            value = Date.parse(date);
          } else if (isNaN(parseInt(data))) {
            value = data.toLowerCase();
          } else {
            value = parseFloat(data);
          }

          collection.push({ index: i, value });
        }

        // sort collection array
        if (sort == "asc") {
          collection.sort((x, y) => {
            return x.value > y.value ? 1 : -1;
          });
        } else if (sort == "desc") {
          collection.sort((x, y) => {
            return x.value < y.value ? 1 : -1;
          });
        }

        // sort table
        collection.forEach((row, index) => {
          table.rows[1 + index].innerHTML =
            origTable.rows[row.index].innerHTML;
        });
      });
    }
  }

  // frontend - pagination
  initTablePagination(table, pagination, classCollection) {
    if (table.classList.contains("ea-advanced-data-table-paginated")) {
      let paginationHTML = "";
      let paginationType = pagination.classList.contains(
        "ea-advanced-data-table-pagination-button"
      )
        ? "button"
        : "select";
      let currentPage = 1;
      let startIndex = table.rows[0].parentNode.tagName.toLowerCase() === "thead" ? 1 : 0;
      let endIndex = currentPage * table.dataset.itemsPerPage;
      let maxPages = Math.ceil( (table.rows.length - 1) / table.dataset.itemsPerPage );
      if (!startIndex) {
        endIndex -= 1;
      }
      pagination.insertAdjacentHTML(
            "beforeend", '');      // insert pagination
      if (maxPages > 1) {
        if (paginationType === "button") {

          paginationHTML += `<a href="#" data-page="1" class="ea-adtp-current ea-adtp-show">1</a><a class="dots-1st ea-adtp-hide">...</a>`;
          for (let i = 2; i < maxPages; i++) {
            let cssClass = i <= 6 || i === maxPages ? 'ea-adtp-show' : 'ea-adtp-hide';
            paginationHTML += `<a href="#" data-page="${i}" class="${cssClass}">${i}</a>`;
          }

          let dots2 = '', cssClass = 'ea-adtp-show';
          if (maxPages > 7) {
            dots2 = `<a class="dots-last">...</a>`;
            cssClass = 'ea-adtp-hide';
          }
          paginationHTML += dots2 + `<a href="#" data-page="${maxPages}" class="${cssClass}">${maxPages}</a>`;

          pagination.insertAdjacentHTML(
              "beforeend",
              `<a href="#" data-page="1" class="ea-adtp-1st">&laquo;</a>${paginationHTML}<a href="#" data-page="${maxPages}" class="ea-adtp-last">&raquo;</a>`
          );
        } else {
          for (let i = 1; i <= maxPages; i++) {
            paginationHTML += `<option value="${i}">${i}</option>`;
          }

          pagination.insertAdjacentHTML(
            "beforeend",
            `<select>${paginationHTML}</select>`
          );
        }
      }

      // make initial item visible
      for (let i = 0; i <= endIndex; i++) {
        if (i >= table.rows.length) {
          break;
        }

        table.rows[i].style.display = "table-row";
      }

      // paginate on click
      if (paginationType === "button") {

        let $ = jQuery;
        $( 'a:not(.dots-1st, .dots-last)', pagination ).on("click", (e) => {
          e.preventDefault();

          if (e.target.tagName.toLowerCase() === "a") {
            currentPage = e.target.dataset.page;
            let offset = table.rows[0].parentNode.tagName.toLowerCase() === "thead" ? 1 : 0;
            startIndex = (currentPage - 1) * table.dataset.itemsPerPage + offset;
            endIndex = currentPage * table.dataset.itemsPerPage;
            if (!offset) {
              endIndex -= 1;
            }
            if (maxPages > 7) {
              let countFrom = 1, countTo = 6;
              $('a.ea-adtp-current', pagination).removeClass('ea-adtp-current');

              if (currentPage > maxPages - 5) {
                countFrom = maxPages - 5;
                countTo = maxPages;
              } else if (currentPage > 5) {
                countFrom = currentPage - 2;
                countTo = parseInt(currentPage) + 2;
              }

              $('a.ea-adtp-show', pagination).removeClass('ea-adtp-show').addClass('ea-adtp-hide');
              for (let page = countFrom; page <= countTo; page++) {
                $(`a[data-page="${page}"]:not(.ea-adtp-1st,.ea-adtp-last)`, pagination).removeClass('ea-adtp-hide').addClass('ea-adtp-show');
              }

              $(`a[data-page="${currentPage}"]`, pagination).addClass('ea-adtp-current');

              if ($(`a[data-page="2"]`, pagination).hasClass('ea-adtp-hide')) {
                $(`a.dots-1st`, pagination).removeClass('ea-adtp-hide').addClass('ea-adtp-show');
              } else {
                $(`a.dots-1st`, pagination).removeClass('ea-adtp-show').addClass('ea-adtp-hide');
              }

              if ($(`a[data-page="${maxPages - 1}"]`, pagination).hasClass('ea-adtp-hide')) {
                $(`a.dots-last`, pagination).removeClass('ea-adtp-hide').addClass('ea-adtp-show');
              } else {
                $(`a.dots-last`, pagination).removeClass('ea-adtp-show').addClass('ea-adtp-hide');
              }
            } else {
              $('a.ea-adtp-current', pagination).removeClass('ea-adtp-current');
              $(`a[data-page="${currentPage}"]`, pagination).addClass('ea-adtp-current');
            }

            for (let i = offset; i <= table.rows.length - 1; i++) {
              if (i >= startIndex && i <= endIndex) {
                table.rows[i].style.display = "table-row";
              } else {
                table.rows[i].style.display = "none";
              }
            }

            table.querySelectorAll("th").forEach((el, index) => {
              el.classList.remove("asc", "desc");

              if (typeof classCollection[currentPage] != "undefined") {
                if (classCollection[currentPage][index]) {
                  el.classList.add(classCollection[currentPage][index]);
                }
              }
            });
          }
        });
      } else {
        if (pagination.hasChildNodes()) {
          pagination.querySelector("select").addEventListener("input", (e) => {
            e.preventDefault();

            currentPage = e.target.value;
            offset =
              table.rows[0].parentNode.tagName.toLowerCase() == "thead" ? 1 : 0;
            startIndex =
              (currentPage - 1) * table.dataset.itemsPerPage + offset;
            endIndex = currentPage * table.dataset.itemsPerPage;

            for (let i = offset; i <= table.rows.length - 1; i++) {
              if (i >= startIndex && i <= endIndex) {
                table.rows[i].style.display = "table-row";
              } else {
                table.rows[i].style.display = "none";
              }
            }

            table.querySelectorAll("th").forEach((el, index) => {
              el.classList.remove("asc", "desc");

              if (typeof classCollection[currentPage] != "undefined") {
                if (classCollection[currentPage][index]) {
                  el.classList.add(classCollection[currentPage][index]);
                }
              }
            });
          });
        }
      }
    }
  }

  // woocommerce features
  initWooFeatures(table) {
    table.querySelectorAll(".nt_button_woo").forEach((el) => {
      el.classList.add("add_to_cart_button", "ajax_add_to_cart");
    });

    table.querySelectorAll(".nt_woo_quantity").forEach((el) => {
      el.addEventListener("input", (e) => {
        let product_id = e.target.dataset.product_id;
        let quantity = e.target.value;

        $(`.nt_add_to_cart_${product_id}`, $(table)).data("quantity", quantity);
      });
    });
  }
}

eael.hooks.addAction("init", "ea", () => {
  if (eael.elementStatusCheck('eaelAdvancedDataTable')) {
    return false;
  }
  new advancedDataTable();
});
