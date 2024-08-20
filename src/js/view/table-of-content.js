(function ($) {
	jQuery(document).ready(function () {
		/**
		 * add ID in main content heading tag
		 * @param selector
		 * @param supportTag
		 */

         var headerHeight = $("header").height();
         var page_offset = $("#eael-toc").data("page_offset");
         var page_offset = ( page_offset !== 0 && page_offset !== undefined ) ? page_offset : null;
         var offsetSpan = (page_offset !== null ) ? page_offset : ((headerHeight !== undefined  && headerHeight!== 0 ) ? ( headerHeight + 10 ) : 120)  ;

		function eael_toc_content(selector, supportTag) {
			var listId = document.getElementById("eael-toc-list");
			if (selector === null || supportTag === undefined || !listId) {
				return null;
			}
			var eaelToc = document.getElementById("eael-toc");
			var titleUrl =
				typeof eaelToc.dataset.titleurl !== "undefined"
					? eaelToc.dataset.titleurl
					: "false";
			var excludeArr =
				typeof eaelToc.dataset.excludeSelector !== "undefined"
					? eaelToc.dataset.excludeSelector.replace(/^,|,$/g, "")
					: "";
			var allSupportTag = [];
			var mainSelector = document.querySelectorAll(selector),
				listIndex = 0;
			for (var j = 0; j < mainSelector.length; j++) {
				allSupportTag = [
					...allSupportTag,
					...mainSelector[j].querySelectorAll(supportTag),
				];
			}
			allSupportTag = Array.from(new Set(allSupportTag));

			allSupportTag.forEach(function (el) {
				if (eaelTocExclude(excludeArr, el)) {
					return;
				}
				el.id = listIndex + "-" + eael_build_id(titleUrl, el.textContent);
				el.classList.add("eael-heading-content");
				listIndex++;
			});
			//build toc list hierarchy
			eael_list_hierarchy(selector, supportTag, allSupportTag);

			var firstChild = $("ul.eael-toc-list > li");
			if (firstChild.length < 1) {
				document.getElementById("eael-toc").classList.add("eael-toc-disable");
			}
			firstChild.each(function () {
				this.classList.add("eael-first-child");
			});
		}

		/**
		 * Make toc list
		 * @param selector
		 * @param supportTag
		 */
		function eael_list_hierarchy(selector, supportTag, allSupportTagList) {
			var tagList = supportTag;
			var allHeadings = allSupportTagList;
			var eaelToc = document.getElementById("eael-toc");
			var titleUrl =
				typeof eaelToc.dataset.titleurl !== "undefined"
					? eaelToc.dataset.titleurl
					: "false";
			var listId = document.getElementById("eael-toc-list");
			var excludeArr =
				typeof eaelToc.dataset.excludeselector !== "undefined"
					? eaelToc.dataset.excludeselector.replace(/^,|,$/g, "")
					: "";
			var parentLevel = "",
				baseTag = (parentLevel = tagList.trim().split(",")[0].substr(1, 1)),
				ListNode = listId;

			listId.innerHTML = "";

			if (allHeadings.length > 0) {
				document
					.getElementById("eael-toc")
					.classList.remove("eael-toc-disable");
			}
			for (var i = 0, len = allHeadings.length; i < len; ++i) {
				var currentHeading  = allHeadings[i],
					exclude_areas   = '.ab-top-menu, .page-header, .site-title, nav, footer, .comments-area, .woocommerce-tabs, .related.products, .blog-author, .post-author, .post-related-posts, .eael-toc-header',
					find_exclude    = $(currentHeading).closest( exclude_areas ),
					in_exclude_area = find_exclude.length > 0;

				if ( eaelTocExclude(excludeArr, currentHeading) || in_exclude_area ) {
					continue;
				}

				var latestLavel = parseInt(currentHeading.tagName.substr(1, 1));
				var diff = latestLavel - parentLevel;

				if (diff > 0) {
					var containerLiNode = ListNode.lastChild;
					if (containerLiNode) {
						var createUlNode = document.createElement("UL");

						containerLiNode.appendChild(createUlNode);
						ListNode = createUlNode;
						parentLevel = latestLavel;
					}
				}

				var sequenceParent = false;

				if (diff < 0) {
					while (0 !== diff++) {
						if (ListNode.parentNode.parentNode) {
							ListNode = ListNode.parentNode.parentNode;
						}
					}
					parentLevel = latestLavel;
					sequenceParent = true;
				}

				if (ListNode.tagName !== "UL") {
					ListNode = listId;
				}

				if (currentHeading.textContent.trim() === "") {
					continue;
				}
				var liNode = document.createElement("LI");
				var anchorTag = document.createElement("A");
				var spanTag = document.createElement("SPAN");

				if (baseTag === parentLevel || sequenceParent) {
					liNode.setAttribute("itemscope", "");
					liNode.setAttribute("itemtype", "http://schema.org/ListItem");
					liNode.setAttribute("itemprop", "itemListElement");
				}

				var Linkid =
					"#" + i + "-" + eael_build_id(titleUrl, currentHeading.textContent);
				anchorTag.className = "eael-toc-link";
				anchorTag.setAttribute("itemprop", "item");
				anchorTag.setAttribute("href", Linkid);
				spanTag.appendChild(
					document.createTextNode(currentHeading.textContent)
				);
				anchorTag.appendChild(spanTag);
				liNode.appendChild(anchorTag);
				ListNode.appendChild(liNode);
			}
		}

		// expand collapse
		$(document).on("click", "ul.eael-toc-list a", function (e) {
			e.preventDefault();

			$(document).off("scroll");

			var target = this.hash;
			history.pushState(
				"",
				document.title,
				window.location.pathname + window.location.search
			);

			var parentLi = $(this).parent();

			if (parentLi.is(".eael-highlight-parent.eael-highlight-active")) {
				$('html, body').animate({
                    scrollTop: $(target).offset().top - offsetSpan
                }, 0);
				return false;
			}

			$(".eael-highlight-active, .eael-highlight-parent").removeClass(
				"eael-highlight-active eael-highlight-parent"
			);

			$(this).closest(".eael-first-child").addClass("eael-highlight-parent");

			$(this).parent().addClass("eael-highlight-active");

			$('html, body').animate({
                scrollTop: $(target).offset().top - offsetSpan
            }, 0);
		});

		//some site not working with **window.onscroll**
		window.addEventListener("scroll", function (e) {
			eaelTocSticky();
		});
		var stickyScroll = $("#eael-toc").data("stickyscroll");

		/**
		 * Check selector in array
		 *
		 * @param arr
		 * @param el
		 * @returns boolean
		 */
		function eaelTocExclude(excludes, el) {
			return $(el).closest(excludes).length;
		}

		/**
		 * check sticky
		 */
		function eaelTocSticky() {
			var eaelToc = document.getElementById("eael-toc");
			if (!eaelToc) {
				return;
			}
			stickyScroll = stickyScroll !== undefined ? stickyScroll : 200;
			if (
				window.pageYOffset >= stickyScroll &&
				!eaelToc.classList.contains("eael-toc-disable")
			) {
				eaelToc.classList.add("eael-sticky");
				
				if($('#eael-toc-list').hasClass('eael-toc-auto-highlight')) {
					highlightCurrentHeading();
				}
			} else {
				eaelToc.classList.remove("eael-sticky");
			}
		}

		function highlightCurrentHeading(){
			var allHeadings = document.querySelectorAll("#eael-toc-list .eael-toc-link");
			$('#eael-toc-list .eael-toc-link').removeClass('eael-highlight-active');
			let showSinlgeHeadingOnly = $('#eael-toc').hasClass('eael-toc-auto-highlight.eael-toc-highlight-single-item') ? true : false;

			for(let i=0; i < allHeadings.length; i++) {
				let headingElement = allHeadings[i];
				let headingTarget = headingElement.getAttribute("href");
				let headingTargettedElement = document.getElementById( headingTarget.substring(1) ); //removes # and fetch element
				
				if(isElementInViewport(headingTargettedElement)){
					$(headingElement).addClass("eael-highlight-active");
					if(showSinlgeHeadingOnly){
						break;
					}
				}
			}
		}

		/**
		 * Determine if the element is in the viewport.
		 * @param {*} el 
		 * @returns 
		 */
		function isElementInViewport (el) {

			// Special bonus for those using jQuery
			if (typeof jQuery === "function" && el instanceof jQuery) {
				el = el[0];
			}
		
			var rect = el.getBoundingClientRect();
		
			return (
				rect.top >= 0 &&
				rect.left >= 0 &&
				rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && /* or $(window).height() */
				rect.right <= (window.innerWidth || document.documentElement.clientWidth) /* or $(window).width() */
			);
		}

		/**
		 *
		 * @param content
		 * @returns {string}
		 */
		function eael_build_id(showTitle, title) {
			if (showTitle == "true" && title != "") {
				//create slug from Heading text
				return title
					.toString()
					.toLowerCase()
					.normalize("NFD")
					.trim()
					.replace(/[^a-z0-9 -]/g, "")
					.replace(/\s+/g, "-")
					.replace(/^-+/, "")
					.replace(/-+$/, "")
					.replace(/-+/g, "-");
			} else {
				return "eael-table-of-content";
			}
		}

		/**
		 *
		 * @returns {null|selector}
		 */
		function eael_toc_check_content() {
			var eaelToc = document.getElementById("eael-toc");
			if (eaelToc && eaelToc.dataset.contentselector) {
				return eaelToc.dataset.contentselector;
			}

			var contentSelectro = ".site-content";
			if ($(".site-content")[0]) {
				contentSelectro = ".site-content";
			} else if ($(".elementor-inner")[0]) {
				contentSelectro = ".elementor-inner";
			} else if ($("#site-content")[0]) {
				contentSelectro = "#site-content";
			}else if ($(".site-main")){
				contentSelectro = ".site-main";
			}
			return contentSelectro;
		}

		//toc auto collapse
		$("body").click(function (e) {
			var target = $(e.target);
			var eaToc = $("#eael-toc");
			if (
				eaToc.hasClass("eael-toc-auto-collapse") &&
				eaToc.hasClass("eael-sticky") &&
				!eaToc.hasClass("collapsed") &&
				$(target).closest("#eael-toc").length === 0
			) {
				eaToc.toggleClass("collapsed");
			}
		});

		$(document).on("click", ".eael-toc-close ,.eael-toc-button", function (
			event
		) {
			event.stopPropagation();
			$(".eael-toc").toggleClass("collapsed");
		});

		function eael_build_toc($settings) {
			var pageSetting = $settings.settings,
				title = pageSetting.eael_ext_toc_title,
				toc_style_class =
					"eael-toc-list eael-toc-list-" +
					pageSetting.eael_ext_table_of_content_list_style,
				icon = pageSetting.eael_ext_table_of_content_header_icon.value,
				el_class =
					pageSetting.eael_ext_toc_position === "right"
						? " eael-toc-right"
						: " ";
			toc_style_class +=
				pageSetting.eael_ext_toc_collapse_sub_heading === "yes"
					? " eael-toc-collapse"
					: " ";
			toc_style_class +=
				pageSetting.eael_ext_toc_list_icon === "number"
					? " eael-toc-number"
					: " eael-toc-bullet";

			return (
				'<div id="eael-toc" class="eael-toc eael-toc-disable ' +
				el_class +
				'">' +
				'<div class="eael-toc-header"><span class="eael-toc-close">×</span><h2 class="eael-toc-title">' +
				title +
				"</h2></div>" +
				'<div class="eael-toc-body"><ul id="eael-toc-list" class="' +
				toc_style_class +
				'"></ul></div>' +
				'<button class="eael-toc-button"><i class="' +
				icon +
				'"></i><span>' +
				title +
				"</span></button>" +
				"</div>"
			);
		}


		if (typeof eael !== 'undefined' && eael.isEditMode){
			elementorFrontend.hooks.addAction(
				"frontend/element_ready/widget",
				function ($scope, jQuery) {
					var tocLoad = jQuery("#eael-toc #eael-toc-list");
					var TocList = tocLoad.find("li.eael-first-child");
					if (TocList.length < 1 && tocLoad.length >= 1) {
						var tagList = jQuery("#eael-toc").data("eaeltoctag");
						if (tagList) {
							eael_toc_content(eael_toc_check_content(), tagList);
						}
					}
				}
			);
		}

		const editMode = (typeof isEditMode !== 'undefined')?isEditMode:false;
		var intSupportTag = $("#eael-toc").data("eaeltoctag");
		if (intSupportTag !== "" && !editMode) {
			eael_toc_content(eael_toc_check_content(), intSupportTag);
		}
	});
})(jQuery);
