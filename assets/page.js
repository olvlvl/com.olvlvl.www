(function() {
	function updateLinkTargets() {
		const links = document.body.querySelectorAll('[href^="http"]')

		for (let i = 0, j = links.length ; i < j ; i++) {
			links[i].target = '_blank'
		}
	}

	function ga () {
		const el = document.createElement('script')

		el.src = "https://www.googletagmanager.com/gtag/js?id=G-1HJ8TFRQ47"
		el.async = true

		document.head.appendChild(el)

		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments)}
		gtag('js', new Date());
		gtag('config', 'G-1HJ8TFRQ47');
	}

	ga()

	window.addEventListener('DOMContentLoaded', ev => {
		updateLinkTargets()
	})

	window.addEventListener('load', ev => {
		textBalancer.initialize('h1, h2')
	})
}) ();
