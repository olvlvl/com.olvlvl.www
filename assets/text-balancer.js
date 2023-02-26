// src: https://github.com/nytimes/text-balancer/blob/master/text-balancer.js
// https://codeberg.org/da/text-balancer

textBalancer = (function () {
	// pass in a string of selectors to be balanced.
	// if you didn't specify any, that's ok! We'll just
	// balance anything with the balance-text class
	function initialize(selectors = '.balance-text') {
		const candidates = createSelectors(selectors)

		const observer = new ResizeObserver(entries => {
			for (const candidate of candidates) {
				for (const entry of entries) {
					if (entry.target.contains(candidate)) {
						balance(candidate)
					}
				}
			}
		})

		for (const el of candidates) {
			observer.observe(el.parentElement)
		}
	}

	function balance(element) {
		if (!textElementIsMultipleLines(element)) {
			return
		}

		element.style.maxWidth = ''
		squeezeContainer(element, element.clientHeight, 0, element.clientWidth)
	}

	// Make the headline element as narrow as possible while maintaining its current height (number of lines). Binary search.
	function squeezeContainer(headline, originalHeight, bottomRange, topRange) {
		if ((bottomRange + 4) >= topRange) {
			headline.style.maxWidth = topRange + 'px'
			return
		}

		const mid = (bottomRange + topRange) / 2

		headline.style.maxWidth = mid + 'px'

		if (headline.clientHeight > originalHeight) {
			// we've squeezed too far and headline has spilled onto an additional line; recurse on wider range
			squeezeContainer(headline, originalHeight, mid, topRange)
		} else {
			// headline has not wrapped to another line; keep squeezing!
			squeezeContainer(headline, originalHeight, bottomRange, mid)
		}
	}

	// this populates our candidates array with dom objects
	// that need to be balanced
	function createSelectors(selectors) {
		const selectorArray = selectors.split(',')
		const candidates = []

		for (const selector of selectorArray) {
			const currentSelectorElements = document.querySelectorAll(selector.trim())

			for (const currentSelectorElement of currentSelectorElements) {
				candidates.push(currentSelectorElement)
			}
		}

		return candidates
	}

	// check if element text spans multiple lines
	function textElementIsMultipleLines(element) {
		const elementStyles = window.getComputedStyle(element)
		const elementLineHeight = parseInt(elementStyles['line-height'], 10)
		const elementHeight = parseInt(elementStyles['height'], 10)

		return elementLineHeight < elementHeight
	}

	return {initialize: initialize}

})()
