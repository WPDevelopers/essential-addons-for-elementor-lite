(function($) {
	jQuery(document).ready(function() {
		/**
		 * add ID in main content heading tag
		 * @param selector
		 * @param supportTag
		 */
		function eael_toc_content(selector, supportTag) {
			if (selector === null || supportTag === undefined) {
				return null;
			}
			var mainSelector = document.querySelector(selector),
				allSupportTag = Array.prototype.slice.call(mainSelector.querySelectorAll(supportTag)),
				listIndex = 0;

			allSupportTag.forEach(function(el) {
				el.id = listIndex + "-" + eael_build_id();
				el.classList.add("eael-heading-content");
				listIndex++;
			});
			eael_list_hierarchy(selector, supportTag);
			var firstChild = $("ul.eael-toc-list > li");
			if (firstChild.length < 1) {
				document.getElementById("eael-toc").classList.add("eael-toc-disable");
			}
			firstChild.each(function() {
				this.classList.add("eael-first-child");
			});
		}

		/**
		 * Make toc list
		 * @param selector
		 * @param supportTag
		 */
		function eael_list_hierarchy(selector, supportTag) {
			var tagList = supportTag;
			var parentLevel = '';
			var listId = document.getElementById("eael-toc-list");
			var mainContent = document.querySelector(selector),

			allHeadings = mainContent.querySelectorAll(tagList),
				baseTag = parentLevel = tagList
					.trim()
					.split(",")[0]
					.substr(1, 1),
				ListNode = listId;

			listId.innerHTML = "";
			if (allHeadings.length > 0) {
				document.getElementById("eael-toc").classList.remove("eael-toc-disable");
			}
			for (var i = 0, len = allHeadings.length; i < len; ++i) {
				var currentHeading = allHeadings[i];
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

				var Linkid = "#" + i + "-" + eael_build_id();
				anchorTag.className = "eael-toc-link";
				anchorTag.setAttribute("itemprop", "item");
				anchorTag.setAttribute("href", Linkid);
				spanTag.appendChild(document.createTextNode(currentHeading.textContent));
				anchorTag.appendChild(spanTag);
				liNode.appendChild(anchorTag);
				ListNode.appendChild(liNode);
			}
		}

		var intSupportTag = $("#eael-toc").data("eaeltoctag");
		if (intSupportTag !== "") {
			eael_toc_content(eael_toc_check_content(), intSupportTag);
		}

		// expand collapse
		$(document).on("click", "ul.eael-toc-list a", function(e) {
			e.preventDefault();

			$(document).off("scroll");

			var target = this.hash;
			history.pushState("", document.title, window.location.pathname + window.location.search);

			var parentLi = $(this).parent();

			if (parentLi.is(".eael-highlight-parent.eael-highlight-active")) {
				window.location.hash = target;
				return false;
			}

			$(".eael-highlight-active, .eael-highlight-parent").removeClass("eael-highlight-active eael-highlight-parent");

			$(this)
				.closest(".eael-first-child")
				.addClass("eael-highlight-parent");

			$(this)
				.parent()
				.addClass("eael-highlight-active");

			window.location.hash = target;
		});

		window.onscroll = function() {
			eaelTocSticky();
		};
		var stickyScroll = $('#eael-toc').data('stickyscroll');

		/**
		 * check sticky
		 */
		function eaelTocSticky() {
			var eaelToc = document.getElementById("eael-toc");
			if (!eaelToc) {
				return;
			}
			stickyScroll = (stickyScroll!==undefined)?stickyScroll:200;
			if (window.pageYOffset >= stickyScroll) {
				eaelToc.classList.add("eael-sticky");
			} else {
				eaelToc.classList.remove("eael-sticky");
			}
		}

		/**
		 *
		 * @param content
		 * @returns {string}
		 */
		function eael_build_id() {
			return "eael-table-of-content";
		}

		/**
		 *
		 * @returns {null|selector}
		 */
		function eael_toc_check_content() {
			var contentSelectro = '.site-content';
			if ($(".elementor-inner")[0]) {
				contentSelectro = ".elementor-inner";
			} else if ($("#site-content")[0]) {
				contentSelectro = "#site-content";
			}
			return contentSelectro;
		}

		//toc auto collapse
		$("body").click(function(e) {
			var target = $(e.target);
			var eaToc = $("#eael-toc");
			if (eaToc.hasClass("eael-toc-auto-collapse") && !eaToc.hasClass("collapsed") && $(target).closest("#eael-toc").length === 0) {
				eaToc.toggleClass("collapsed");
			}
		});

		$(document).on("click", ".eael-toc-close ,.eael-toc-button", function(event) {
			event.stopPropagation();
			$(".eael-toc").toggleClass("collapsed");
		});

		function eael_build_toc($settings) {
			var pageSetting = $settings.settings,
				title = pageSetting.eael_ext_toc_title,
				toc_style_class = "eael-toc-list eael-toc-list-" + pageSetting.eael_ext_table_of_content_list_style,
				support_tag = pageSetting.eael_ext_toc_supported_heading_tag.join(", "),
				icon = pageSetting.eael_ext_table_of_content_header_icon.value,
				el_class = pageSetting.eael_ext_toc_position === "right" ? " eael-toc-right" : " ";
			toc_style_class += pageSetting.eael_ext_toc_collapse_sub_heading === "yes" ? " eael-toc-collapse" : " ";
			toc_style_class += pageSetting.eael_ext_toc_list_icon === "number" ? " eael-toc-number" : " eael-toc-bullet";

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

		//editor mode
		if (isEditMode) {
			elementor.settings.page.addChangeCallback("eael_ext_table_of_content", function(newValue) {
				var tocGlobal = $(".eael-toc-global");
				if (tocGlobal.length > 0) {
					tocGlobal
						.attr("id", "eael-toc-temp")
						.removeClass("eael-toc")
						.hide();
					$(".eael-toc-global #eael-toc-list").attr("id", "");
				}
				$("#eael-toc").remove();
				if (newValue === "yes") {
					var $settings = elementor.settings.page.getSettings();
					$("body").append(eael_build_toc($settings));
					eael_toc_content(eael_toc_check_content(), $settings.settings.eael_ext_toc_supported_heading_tag.join(", "));
				} else {
					if (tocGlobal.length > 0) {
						tocGlobal
							.addClass("eael-toc")
							.attr("id", "eael-toc")
							.show();
					}
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_position", function(newValue) {
				if (newValue === "right") {
					$("#eael-toc").addClass("eael-toc-right");
				} else {
					$("#eael-toc").removeClass("eael-toc-right");
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_table_of_content_list_style", function(newValue) {
				var list = $(".eael-toc-list");
				list.removeClass("eael-toc-list-bar eael-toc-list-arrow");
				if (newValue !== "none") {
					list.addClass("eael-toc-list-" + newValue);
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_collapse_sub_heading", eael_toc_list_collapse);

			function eael_toc_list_collapse(newValue) {
				var list = $(".eael-toc-list");
				if (newValue === "yes") {
					list.addClass("eael-toc-collapse");
				} else {
					list.removeClass("eael-toc-collapse");
				}
			}

			elementor.settings.page.addChangeCallback("eael_ext_table_of_content_header_icon", function(newValue) {
				var iconElement = $(".eael-toc-button i");
				iconElement.removeClass().addClass(newValue.value);
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_list_icon", function(newValue) {
				var list = $(".eael-toc-list");
				if (newValue === "number") {
					list.addClass("eael-toc-number").removeClass("eael-toc-bullet");
				} else {
					list.addClass("eael-toc-bullet").removeClass("eael-toc-number");
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_word_wrap", function(newValue) {
				var list = $(".eael-toc-list");
				if (newValue === "yes") {
					list.addClass("eael-toc-word-wrap");
				} else {
					list.removeClass("eael-toc-word-wrap");
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_close_button_text_style", function(newValue) {
				var toc = $("#eael-toc");
				if (newValue === "bottom_to_top") {
					toc.addClass("eael-bottom-to-top");
				} else {
					toc.removeClass("eael-bottom-to-top");
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_box_shadow", function(newValue) {
				var toc = $("#eael-toc");
				if (newValue === "yes") {
					toc.addClass("eael-box-shadow");
				} else {
					toc.removeClass("eael-box-shadow");
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_auto_collapse", function(newValue) {
				var toc = $("#eael-toc");
				if (newValue === "yes") {
					toc.addClass("eael-toc-auto-collapse");
				} else {
					toc.removeClass("eael-toc-auto-collapse");
				}
			});

			elementor.settings.page.addChangeCallback("eael_ext_toc_title", ea_toc_title_change);

			function ea_toc_title_change(newValue) {
				elementorFrontend.elements.$document.find(".eael-toc-title").text(newValue);
				elementorFrontend.elements.$document.find(".eael-toc-button span").text(newValue);
			}
		}
	});
})(jQuery);
