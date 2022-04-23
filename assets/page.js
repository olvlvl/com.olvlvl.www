(function() {
	window.addEventListener('DOMContentLoaded', ev => {
		const links = document.body.querySelectorAll('[href^="http"]')

		for (let i = 0, j = links.length ; i < j ; i++) {
			links[i].target = '_blank'
		}
	})

	window.addEventListener('load', ev => {
		textBalancer.initialize('h1, h2');
	})
}) ();
