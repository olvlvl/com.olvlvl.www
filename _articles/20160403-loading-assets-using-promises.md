# Loading style sheet and JavaScript assets using promises

In my quest of making [Brickrouge][] framework agnostic, I entered the new and exiting territory of
[ECMAScript 6][] and its [promises][], and I'm never looking back! NEVER!

## A tedious affair without promises

Loading style sheet and JavaScript assets, then executing a callback was a tedious affair. I add to
initialize a counter with the number of assets I needed to load, then decrement this counter after
each success, to finally call the callback with the counter reaching zero. Needless to say, I didn't
even implemented an error handler. It was working, but it was a mess:

```js
// MooTools.Assets is included before

/**
 * @param {Array<string>} css
 * @param {Array<string>} js
 * @param {Function} done
 */
function loadAssets(css, js, done) {
	var count = css.length + js.length

	if (!count) {
		done()
		return
	}

	function decrement()
	{
		if (!--count) throw "done"
	}

	try
	{
		css.each(function (url) {

			new Asset.css(url, { onload: decrement })

		})

		js.each(function (url) {

			new Asset.js(url, { onload: decrement })

		})
	}
	catch (e)
	{
		done()
	}
}
```

## Promises kept

It's a completely different affair with promises, not only the code is super tiny, but success and
failure are now handled properly and with detail because callbacks receive an array with the
resolved or rejected assets urls.

Look at this beauty:

```js
const assetsPromises = require('olvlvl-assets-promises')
const StyleSheetPromise = assetsPromises.StyleSheetPromise
const JavaScriptPromise = assetsPromises.JavaScriptPromise

/**
 * @param {Array<string>} css
 * @param {Array<string>} js
 * @param {Function} resolved
 * @param {Function} rejected
 */
function loadAssets(css, js, resolved, rejected) {

	return Promise.all([

		StyleSheetPromise.all(css),
		JavaScriptPromise.all(js)

	]).then(resolved).catch(rejected)

})
```

## A package to load assets using promises

[The package I created][] can help you load your assets using promises. It's super simple to use,
and you can load one or multiple style sheet or JavaScript assets, or both at the same time like in
the previous example. You can learn more about this in the [README][].

The package can be installed with [npm][]:

```bash
$ npm install olvlvl-assets-promises --save
```

I'm currently using it with [Brickrouge][] and [Icybee][], when I need to load assets, to build
custom elements and it's working great! I hope you will enjoy it too.





[Brickrouge]:            http://brickrouge.org/
[ECMAScript 6]:          http://www.ecma-international.org/ecma-262/6.0/index.html
[Icybee]:                http://icybee.org/
[README]:                https://github.com/olvlvl/assets-promises#readme
[The package I created]: https://www.npmjs.com/package/olvlvl-assets-promises
[npm]:                   https://www.npmjs.com/
[promises]:              https://developer.mozilla.org/en/docs/Web/JavaScript/Reference/Global_Objects/Promise
