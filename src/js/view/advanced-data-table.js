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

    if (!ea.isEditMode && table !== null) {
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
            $(this).html(text.replace("<script>", "").replace("</script>", "").replace("<script", ""));
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
    table = $(table);
    if(table.hasClass('ea-advanced-data-table-sortable')){
      table.click(function(e) {
        var th = $(e.target).closest("th");
        if (!th.length) return; // click wasn't on a table header
    
        var table = th.closest("table");
        var tbody = table.find("tbody");
        var rows = tbody.find("tr");
    
        var sortOrder = 1;
        if (th.hasClass("asc")) {
          sortOrder = -1;
          th.removeClass("asc").addClass("desc");
        } else {
          th.removeClass("desc").addClass("asc");
        }

        var dateRegexes = [
          /^\d{1,2}\/\d{1,2}\/\d{4}$/,  // MM/DD/YYYY
          /^\d{4}-\d{1,2}-\d{1,2}$/,  // YYYY-MM-DD
          /^\d{1,2}-\d{1,2}-\d{4}$/,  // DD-MM-YYYY (common in Europe)
          /^\d{4}\/\d{1,2}\/\d{1,2}$/,  // YYYY/MM/DD
          /^([a-zA-Z]{3,}) (\d{1,2}), (\d{4})$/, // Month Day, Year (e.g., Feb 14, 2024)
          /^(\d{1,2}) ([a-zA-Z]{3,}) (\d{4})$/, // Day Month Year (e.g., 14 Feb 2024)
          /^([a-zA-Z]{3,}) (\d{1,2})st, (\d{4})$/,  // Month Dayst, Year
          /^([a-zA-Z]{3,}) (\d{1,2})nd, (\d{4})$/,  // Month Daynd, Year
          /^([a-zA-Z]{3,}) (\d{1,2})rd, (\d{4})$/,  // Month Dayrd, Year
          /^([a-zA-Z]{3,}) (\d{1,2})th, (\d{4})$/   // Month Dayth, Year
        ];

       function isLikelyDate(value) {

          // Apply regular expressions first
          for (var i = 0; i < dateRegexes.length; i++) {
            if (dateRegexes[i].test(value)) {
              return true;
            }
          }
        
          // Heuristic checks (if regular expressions don't match)
          var parts = value.split(/[^\d]+/); // Split by non-digits
          if (parts.length >= 3) {
            var year = parseInt(parts.pop());
            if (year >= 1000 && year <= 9999) { // Basic year range check
              var month = parseInt(parts.pop());
              var day = parseInt(parts.pop());
              if (month >= 1 && month <= 12 && day >= 1 && day <= 31) {
                return true;
              }
            }
          }
          return false;
        }
    
        rows.sort(function(a, b) {
          var colIndex = th.index();
          var valueA = $(a).children().eq(colIndex).text().toUpperCase();
          var valueB = $(b).children().eq(colIndex).text().toUpperCase();

          if (isLikelyDate(valueA) && isLikelyDate(valueB)) {
            // Both are likely dates, sort by parsed date
            var dateA = new Date(valueA);
            var dateB = new Date(valueB);
            return (dateA - dateB) * sortOrder;
          } else if (isLikelyDate(valueA)) {
            // Only A is a likely date, sort A before B
            return -1 * sortOrder;
          } else if (isLikelyDate(valueB)) {
            // Only B is a likely date, sort B before A
            return 1 * sortOrder;
          } else {
            // Handle numeric sorting
            var isNumericA = !isNaN(valueA);
            var isNumericB = !isNaN(valueB);
            if (isNumericA && isNumericB) {
              return (valueA - valueB) * sortOrder;
            }
          }
          
          // Handle case-insensitive text sorting
          return (valueA < valueB) ? (-1 * sortOrder) : (valueA > valueB) ? sortOrder : 0;
        });

        if (pagination && pagination.innerHTML.length > 0) {
          let itemsPerPage   = table.data('items-per-page'),
              paginationType = $(pagination).hasClass( "ea-advanced-data-table-pagination-button" ) ? "button": "select",
              currentPage    =  startIndex = 1,
              endIndex       = rows.length;
          
          currentPage = paginationType == "button" ? $( '.ea-adtp-current', pagination ).data('page') : $("select", pagination).val();

          startIndex = (currentPage - 1) * itemsPerPage;
          endIndex = endIndex - (currentPage - 1) * itemsPerPage >= itemsPerPage ? currentPage * itemsPerPage : endIndex;

          $(rows).removeAttr('style');
          for (let i = startIndex; i < endIndex; i++) {
              $(rows[i]).attr('style', 'display: table-row;');
          }
        }

        tbody.empty().append(rows);
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

ea.hooks.addAction("init", "ea", () => {
  if (ea.elementStatusCheck('eaelAdvancedDataTable')) {
    return false;
  }
  new advancedDataTable();
});
