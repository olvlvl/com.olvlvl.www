!function() {

	var links = document.body.querySelectorAll('[href^="http"]')

	for (var i = 0, j = links.length ; i < j ; i++)
	{
		links[i].target = '_blank'
	}

} ()
