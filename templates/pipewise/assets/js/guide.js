// Builds the "In this guide" table of contents from the article's <h2> headings.
(function () {
	var body = document.querySelector(".guide-body .prose");
	var toc = document.getElementById("guide-toc");
	if (!body || !toc) return;

	var headings = body.querySelectorAll("h2");
	if (headings.length < 2) return;

	var used = {};
	Array.prototype.forEach.call(headings, function (h) {
		var base = h.id || h.textContent.toLowerCase().replace(/[^a-z0-9]+/g, "-").replace(/^-+|-+$/g, "") || "section";
		var id = base;
		var n = 2;
		while (used[id]) {
			id = base + "-" + n++;
		}
		used[id] = true;
		h.id = id;

		var a = document.createElement("a");
		a.href = "#" + id;
		a.textContent = h.textContent;
		toc.appendChild(a);
	});
	toc.hidden = false;
})();
