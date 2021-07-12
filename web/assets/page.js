!function() {
	const links = document.body.querySelectorAll('[href^="http"]')

	for (let i = 0, j = links.length ; i < j ; i++) {
		links[i].target = '_blank'
	}

	window.addEventListener('load', function () {
		balanceText('h1, h2', { watch: true })
	})
} ()
