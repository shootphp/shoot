# The optional tag
When using Shoot, your presenters load data as needed by each individual template. This is a process that might fail for
various reasons. An external resource – such as a database or API – might be down or slow to respond, resulting in a
runtime exception.

You could catch and handle this exception in your presenter, but you'd still be left without the data for your template
– which would then fail to render.

If you don't, Twig will catch the exception and rethrow it, wrapped in an instance of `Twig_Error_Runtime`. This of
course will abort rendering, leaving you to either try again or serve your visitor an error page.

What if that exception wasn't such a big deal? What if the template you were trying to render was not essential to the
page? Ideally, you'd be able to just leave it out and serve the rest of the page as-is. That's probably of more use to
your visitors than a puzzling _"Internal Server Error"_.

Introducing: the `optional` tag.

## How to use it
Quite simple, really. Just wrap any included templates that are not essential to the page in the `optional` tag:

```twig
<body>
    <main>
        {% include 'super_important.twig' %}
    </main>

    {% optional %}
    <aside>
        {% include 'nice_to_have.twig' %}
    </aside>
    {% endoptional %}
</body>
```

Any _runtime_ exception thrown within the `optional` tag will be caught and suppressed. Rendering of all content in the
tag is aborted, but everything outside of it will proceed.

## Accessing the exceptions
Suppressed exceptions can be accessed from Shoot's middleware. Call `hasSuppressedException` on the `View` instance to
see if an exception was suppressed. The exception itself is available through `View::getSuppressedException`.

The `LoggingMiddleware` included with Shoot will log suppressed exceptions as warnings.

## Keep in mind
If an exception is thrown, all contents of the tag will be discarded. Make sure that doesn't break the page as a result
of invalid HTML, or CSS or JavaScript depending on the elements in the tag being present.

Any exception causes all of the contents to be discarded. Don't create catch-all blocks, as you'd be throwing away more
of the page than is necessary. Encapsulate specific blocks you can do without.
