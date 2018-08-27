# Shoot
[![License][ico-license]][link-license]
[![Coverage][ico-coverage]][link-coverage]
[![Code quality][ico-code-quality]][link-code-quality]

Shoot is an extension for [Twig][link-twig], a popular template engine for PHP. Shoot aims to make providing data to
your templates more manageable. Think of Shoot as a DI container for template data. 

## Prerequisites
Shoot assumes you're using PHP 7 and Twig to render templates in a [PSR-7][link-psr7] HTTP context. It also needs a
[PSR-11][link-psr11] compatible DI container.

Although not a requirement, a framework with support for [PSR-15][link-psr15] HTTP middleware does make your life a
little easier.

## What it does
Typically, you first load your data and then use Twig to render that data into HTML. Shoot turns that around. You start
rendering your templates and Shoot loads the data as needed. Enjoy this ASCII illustration:

```
+---------------+          +---------------+
|    Request    |          |    Request    |
+-------+-------+          +-------+-------+
        |                          |     +---------+
        |                          |     |         |
+-------v-------+          +-------v-----v-+     +-+-------------+
|   Load data   |          |  Render view  +----->   Load data   |
+-------+-------+          +-------+-------+     +---------------+
        |                          |
        |                          |
+-------v-------+          +-------v-------+
|  Render view  |          |   Response    |
+-------+-------+          +---------------+
        |
        |
+-------v-------+
|   Response    |
+---------------+
```

For this to work, Shoot introduces a few concepts:
* _Presentation models_ – Think of them as data contracts for your templates, ie. _Views_.
* _Presenters_ – These do the actual work. A presenter is coupled to a specific presentation model, and loads just the
data it needs. These presenters are automatically invoked by Shoot as your templates are rendered.
* _Middleware_ – As each template is rendered, it passes through Shoot's middleware pipeline. Invoking the presenters is
done by middleware, but there are plenty of other use cases, such as logging and debugging.

## Installation
Shoot is available through [Packagist][link-packagist]. Simply install it with Composer:
``` bash
$ composer require shoot/shoot
```

## Getting started
First, set up the pipeline. All views being rendered by Twig pass through it, and are processed by Shoot's middleware.
For Shoot to be useful, you'll need at least the `PresenterMiddleware`, which takes a DI container as its dependency.

All that's left is then to add the extension to Twig:

```php
$middleware = [new PresenterMiddleware($container)];
$pipeline = new Pipeline($middleware);
$extension = new Extension($pipeline);

$twig->addExtension($extension);
```

With Shoot now set up, let's take a look at an example of how you can use it.

### Request context
Before we're able to use Shoot's pipeline, it needs the current HTTP request being handled to provide context to its
middleware and the presenters. You set it through the `withRequest` method, which accepts the request and a callback as
its arguments. The callback is immediately executed and its result returned. During the execution of the callback, the
request is available to the pipeline.

```php
$result = $pipeline->withRequest($request, function () use ($twig): string {
    return $twig->render('template.twig');
});
```

In the example above, `result` will contain the rendered HTML as returned by Twig.

To avoid having to manually set the request on the pipeline everywhere you render a template, it's recommended to handle
this in your HTTP middleware. This way, it's always taken care of. Shoot comes with PSR-15 compatible middleware to do
just that: `Shoot\Shoot\Http\ShootMiddleware`.

### Presentation models
Now with the plumbing out of the way, it's time to create our first presentation model. We'll use a blog post for our
example:

```php
namespace Blog;

final class Post extends PresentationModel implements HasPresenterInterface
{
    protected $author_name = '';
    protected $author_url = '';
    protected $body = '';
    protected $title = '';

    public function getPresenterName(): string
    {
        return PostPresenter::class;
    }
}
```

The fields in a presentation model are the variables that'll be assigned to your template. That's why, as per Twig's
[code standard][link-twig-code-standard], they use _snake_case_. These fields must be `protected`.

This presentation model implements the `HasPresenterInterface`. This indicates to Shoot that there's a presenter
available to load the data of this model. This interface defines the `getPresenterName` method. This method should
return the name through which the presenter can be resolved by your DI container.


### Templates
To assign the model to our template, we use Shoot's `model` tag. Set it at the top of your template and reference the
class name of your model:

```twig
{% model 'Blog\\Post' %}
<!doctype html>
<html>
    <head>
        <title>{{ title }}</title>
    </head>
    <body>
        <h1>{{ title }}</h1>
        <p>Written by <a href="{{ author_url }}">{{ author_name }}</a></p>
        <p>{{ body }}</p>
    </body>
</html>
```

### Presenters
With the presentation model defined and assigned to the template, we can now focus on writing the presenter. Since
presenters are retrieved from your DI container, you can easily inject any dependencies needed to load your data. In the
following example, we need a database and router:

```php
namespace Blog;

final class PostPresenter implements PresenterInterface
{
    private $database;
    private $router;

    public function __construct(PDO $database, Router $router)
    {
        $this->database = $database;
        $this->router = $router;
    }

    public function present(ServerRequestInterface $request, PresentationModel $presentationModel): PresentationModel
    {
        // post_id could be a variable in our route, e.g. /posts/{post_id}
        $postId = $request->getAttribute('post_id', '');

        $post = $this->fetchPost($postId);

        return $presentationModel->withVariables([
            'author_name' => $post['author_name'],
            'author_url' => $this->router->pathFor('author', $post['author_id']),
            'body' => $post['body'],
            'title' => $post['title']
        ]);
    }

    private function fetchPost(string $postId): array
    {
        // Fetches the post from the database
    }
}
```

Whenever the template is rendered, the presenter's `present` method will be called by Shoot with the current request
and the presentation model assigned to the template.

It will fetch the necessary data from the database, look up the correct route to the author's profile and return the
presentation model updated with its variables set. Shoot then assigns these variables to the template, and Twig takes
care of rendering it. Job done!

## Limitations
* Do not define a model for templates from which you intend to extend. Due to how Twig renders its templates, it causes
the presentation model of a child template to be overwritten by its parent. This is probably not what you want. 

## Changelog
Please see the [changelog][link-changelog] for more information on what has changed recently.

## Testing
``` bash
$ composer run-script test
```

## License
The MIT License (MIT). Please see the [license file][link-license] for more information.

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-coverage]: https://img.shields.io/scrutinizer/coverage/g/shootphp/shoot.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/shootphp/shoot.svg?style=flat-square
[link-changelog]: CHANGELOG.md
[link-coverage]: https://scrutinizer-ci.com/g/shootphp/shoot/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/shootphp/shoot
[link-license]: LICENSE.md
[link-packagist]: https://packagist.org/packages/shoot/shoot
[link-psr7]: https://www.php-fig.org/psr/psr-7/
[link-psr11]: https://www.php-fig.org/psr/psr-11/
[link-psr15]: https://www.php-fig.org/psr/psr-15/
[link-twig]: https://twig.symfony.com/
[link-twig-code-standard]: https://twig.symfony.com/doc/2.x/coding_standards.html

