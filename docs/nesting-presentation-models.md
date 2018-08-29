# Nesting presentation models
In some cases, it makes sense to pass down data from a parent template to an included template, instead of using a
presenter to load the data for the included template. One such case is that of lists of items. If you'd let each item
fetch its own data, you'd end up hitting your data source more than necessary.

By making the parent template – and its associated presentation model and presenter – responsible for the list as a
whole, we can easily circumvent this problem. Let's look at an example.

## Example
Below, you'll find a presentation model for a shopping cart. The shopping cart itself is a list of the items that have
been added to it, so that's what our model represents.

```php
class ShoppingCart extends PresentationModel implements HasPresenterInterface
{
    /** @var ShoppingCartItem[] */
    protected $items = [];

    public function getPresenterName(): string
    {
        return ShoppingCartPresenter::class;
    }
}
```

For the sake of simplicity, an item is just a name and quantity:

```php
class ShoppingCartItem extends PresentationModel
{
    protected $name = '';
    protected $quantity = 0;
}
```

As you can see, we've nested one model (the item) into the other (the list). This is the recommended approach for data
you intend to pass on to other templates. Another thing to note is that `ShoppingCartItem` doesn't implement the
`HasPresenterInterface`, as we leave that up to the `ShoppingCart`. Here's its presenter:

```php
class ShoppingCartPresenter implements PresenterInterface
{
    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel
    {
        $sessionId = $request->getCookieParams()['session'] ?? '';

        return $presentationModel->withVariables([
            'items' => $this->fetchShoppingCartItemsForSession($sessionId)
        ]);
    }

    /** @return ShoppingCartItem[] */
    private function fetchShoppingCartItemsForSession(string $sessionId): array
    {
        // Fetches the shopping cart items from a data store
    }
}
```

So far, so good. All of this should be pretty straightforward. All that's left is to create our templates. Here's the
template for the shopping cart:

```twig
{% model 'ShoppingCart' %}
<ul>
{% for item in items %}
    {% include 'item.twig' with item|variables only %}
{% endfor %}
</ul>
```

This is all pretty standard stuff. We simply [include][link-include] the template for our items, and pass along the
item. The thing to note here is the use of Shoot's `variables` filter. What this does, is call the `getVariables` method
on the item's presentation model. Think of it as shorthand for:

```twig
{% model 'ShoppingCart' %}
<ul>
{% for item in items %}
    {# DON'T DO THIS! #}
    {% include 'item.twig' with {
        'name': item.getVariable('name'),
        'quantity': item.getVariable('quantity', 0)
    } only %}
{% endfor %}
</ul>
```

The latter has the downside that you'll have to keep maintaining the variables you pass on. That's why using the
`variables` filter is the recommended way of passing on models to an included template.

Finally, the template for our items:

```twig
{% model 'ShoppingCartItem' %}
<li>{{ quantity }}x {{ name }}</li>
```

We still use the `model` tag to set the presentation model for this template. Even though it isn't used with a
presenter, it still acts as a data contract. Ensuring all – and only – the variables defined in our model are available
to the template.

## Conclusion
Although Shoot is designed to allow you to fetch data per template, in isolation of other templates, there are
situations where this is not ideal. Hopefully, the example shown here helps you recognise these situations and provides
you with a solution that fits within Shoot's way of working.

[link-include]: https://twig.symfony.com/doc/2.x/tags/include.html
