document.addEventListener("DOMContentLoaded", function() {

	var links = document.body.querySelectorAll('[href^="http"]')

	for (var i = 0, j = links.length ; i < j ; i++)
	{
		links[i].target = '_blank'
	}

	balanceText(document.body.querySelector('h1'), { watch: true })

	document.body.querySelectorAll('h2').forEach(function (el) {
		balanceText(el, { watch: true })
	})

})
