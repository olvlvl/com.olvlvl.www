# Caching template fragments

In my ever lasting quest to improve the response time of [Icybee][], I introduced a new markup that,
with very little effort, caches rendered template fragments.

<!-- body -->

Icybee uses [Patron][] as its primary rendering engine. Its templates are defined using special HTML
tags, as demonstrated in the following example:

```html
<p:cached>
	<div class="article #{@css_class('-slug')} clearfix">
		<div class="article-inner">
			<h2 class="article-title"><a href="#{@url}" title="Permalink to #{@title}">#{@title}</a></h2>

			<div class="info">
				Posted in <a href="#{@category.url}" rel="subsection" title="All articles in #{@category}">#{@category}</a>
				by <a href="#{@user.url('profile')}" rel="subsection" title="All articles of #{@user.name}">
				#{@user.name}</a>
			</div>

			<p:call-template name="article-aside" />

			<div class="article-body">#{@=}</div>
		</div>
	</div>
</p:cached>	
```

The idea is simple: wrap everything you want to cache in a `p:cached` markup. The enclosed template
and the subject to render are used to create the cache key, if a fresh content exists in the cache
it is used, otherwise the template is rendered and the cache is updated.

This is working great for single records, and record collections as well since they provide the
conditions used to fetch the records.

With this simple, care free implementation response time has been reduced from 90ms to 33ms on the
landing page of one of my biggest blog.

Massive success!





[Icybee]: http://icybee.org/
[Patron]: https://github.com/Icybee/Patron/
