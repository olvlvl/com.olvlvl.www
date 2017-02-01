# A story of proper naming

This afternoon, I was reviewing a colleague's PR and commented that his `RecipesCollection` should be
a `RecipeCollection` because "a collection of records" is "a record collection". Surprisingly, I
never questioned similar classes, such as repositories, in our application. For instance, we have a
`CuisinesRepository`, which was fine by me a few days back while I was refactoring it…

I guess it stuck in my head, because tonight, while reading [Implementing Domain-Driven Design][],
I noticed [Vaughn Vernon][] was writing about `Product` and `ProductRepository`,
not `ProductsRepository`. Intrigued, I Googled a bit and found a similar example in
[Doctrine's documentation][]. I also found this [perfect answer on StackExchange][]:

> When we change the plural noun at the end of the prepositional phrase into a modifier before
the singular noun, the modifier loses the _s_ that it had as a plural noun.

Now I bid you farewell, I have some refactoring to do…





[Implementing Domain-Driven Design]: https://www.safaribooksonline.com/library/view/implementing-domain-driven-design/9780133039900/
[Vaughn Vernon]: https://twitter.com/vaughnvernon
[perfect answer on StackExchange]: http://english.stackexchange.com/questions/229197/what-to-use-plural-or-singular-in-documents-repository
[Doctrine's documentation]: http://symfony.com/doc/current/doctrine/repository.html
