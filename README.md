# Shoot
[![License][ico-license]][link-license]
[![Coverage][ico-coverage]][link-coverage]
[![Code quality][ico-code-quality]][link-code-quality]

Shoot is an extension for Twig which allows you to:
* Define presentation models. Think of them as data contracts for your templates (views).
* Attach presenters to presentation models to load the data used by your views. This is like dependency injection, but
for data.
* Plug into the view rendering process with helpful middleware to log, monitor, cache, and debug.

In the drawing below, you see your typical MVC application on the left. A request is passed on to a controller which
loads whatever data it needs, renders the view, and passes the result on to the response.

On the right, we see the same process, but with Shoot. It applies inversion of control to the view rendering process:
for each view we render, we load whatever data is needed through the use of presenters. The result is that only the data
that's actually needed is loaded. This also means you no longer need to pass data to views just because their children
happen to need it.

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

## Install
``` bash
$ composer require shoot/shoot
```

## Usage
First, set up Shoot's pipeline. All views being rendered pass through it, and are processed by its middleware. For Shoot
to be useful, you'll need at least the PresenterMiddleware, which takes a PSR-11 compliant DI container as its
dependency. All that's left is then to add it to Twig as an extension:

```php
$pipeline = new Pipeline([new PresenterMiddleware($container)]);

$twig->addExtension($pipeline);
```

Below you'll find a request handler serving blog posts. Note the current request being set for the pipeline. The
request is available to all middleware and presenters. You'll see how it's used further down.

```php
$app->get('/posts/{post_id}', function ($request, $response) {
    return $this
        ->get(Pipeline::class)
        ->withRequest($request, function () use ($response) {
            return $this->view->render($response, 'post.twig');
        });
});
```

For the purpose of this example, we use a very basic template:

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

The model tag assigns the following presentation model to the template. Note the use of snake case for the class fields.
These fields will be passed on to the template as variables. Since Twig uses snake case for its variable names, we stick
to that convention here as well.

```php
final class Post extends PresentationModel implements HasPresenter
{
    protected $author_name = '';

    protected $author_url = '';

    protected $body = '';

    protected $title = '';

    public function getPresenter(): string
    {
        return PostPresenter::class;
    }
}
```

The presentation model in turn has the following presenter. Since presenters are loaded from a DI container, each
presenter can have its own dependencies to load and format data.

```php
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

Now, whenever the template is rendered, the presenter will be called to populate the presentation model, which will pass
on its variables to the template.

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
